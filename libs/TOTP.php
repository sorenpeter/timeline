<?php
function verifyTOTP($secret, $enteredCode, $digits = 10, $window = 1) {
	$windowTime = 30;
	$timeSlice = floor(time() / $windowTime);

	$enteredCode = trim($enteredCode);
	$enteredCode = str_replace(' ', '', trim($enteredCode));

	$enteredCodeInt = intval($enteredCode);
	#var_dump($enteredCodeInt);
	#echo "<br>\n";

	for ($currentWindow = -$window; $currentWindow <= $window; $currentWindow++) {
		$time = $timeSlice + $currentWindow;
		$generatedCode = generateTOTP($secret, $digits, $time);
		#echo "$time $generatedCode<br>\n";

		if ($generatedCode === $enteredCodeInt) {
			return true; // Code is valid within the window
		}
	}

	return false; // Code is not valid
}

function generateTOTP($secret, $digits = 6, $timeSlice = null) {
	if ($timeSlice === null) {
		$timeSlice = floor(time() / 30);
	}

	$secret = base32Decode($secret);
	$timeSlice = pack('N*', 0) . pack('N*', $timeSlice);

	$hash = hash_hmac('sha1', $timeSlice, $secret, true);
	$offset = ord($hash[19]) & 0xf;

	$otp = (
		(ord($hash[$offset + 0]) & 0x7f) << 24 |
		(ord($hash[$offset + 1]) & 0xff) << 16 |
		(ord($hash[$offset + 2]) & 0xff) << 8 |
		(ord($hash[$offset + 3]) & 0xff)
	);

	#var_dump($otp);
	#echo "<br>\n";

	// When digits is 10, the padding was giving a wrong OTP
	// Just return the calculated value
	if ($digits === 10) {
		return $otp;
	}

	$otp = $otp % pow(10, $digits);
	return str_pad($otp, $digits, '0', STR_PAD_LEFT);
}

function base32Decode($base32) {
	$base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	$base32charsFlipped = array_flip(str_split($base32chars));
	$paddingChar = '=';
	$paddingCharCount = 0;

	if (strpos($base32, $paddingChar) !== false) {
		$paddingCharCount = substr_count($base32, $paddingChar);
	}

	$allowedValues = array(6, 4, 3, 1, 0);

	if (!in_array($paddingCharCount, $allowedValues)) {
		return false;
	}

	for ($i = 0; $i < 4; ++$i) {
		if ($paddingCharCount == $allowedValues[$i] &&
				substr($base32, -$allowedValues[$i]) != str_repeat($paddingChar, $allowedValues[$i])
				) {
			return false;
		}
	}

	$base32 = str_replace($paddingChar, '', $base32);
	$base32 = str_split($base32);
	$binaryString = '';

	foreach ($base32 as $char) {
		if (!isset($base32charsFlipped[$char])) {
			return false; // Invalid character found
		}
		$binaryString .= sprintf('%05b', $base32charsFlipped[$char]);
	}

	$length = strlen($binaryString);
	$offset = 0;
	$binaryData = '';

	while ($offset < $length) {
		$binaryChunk = substr($binaryString, $offset, 8);

		if (strlen($binaryChunk) < 8) {
			$binaryChunk = str_pad($binaryChunk, 8, '0', STR_PAD_RIGHT);
		}

		$decimalChunk = bindec($binaryChunk);
		$binaryData .= pack('C', $decimalChunk);
		$offset += 8;
	}

	return $binaryData;
}

function base32Encode($input) {
	$base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	$base32String = '';
	$position = 0;
	$carry = 0;
	foreach (str_split($input) as $byte) {
		$carry |= ord($byte) << $position;
		$position += 8;
		while ($position >= 5) {
			$base32String .= $base32Chars[$carry & 31];
			$carry >>= 5;
			$position -= 5;
		}
	}
	if ($position > 0) {
		if ($carry & 31) {
			$base32String .= $base32Chars[$carry & 31];
		}
	}
	return $base32String;
}

function generateRandomSecret($length = 32) {
	// https://medium.com/@nicola88/two-factor-authentication-with-totp-ccc5f828b6df
	$bytes = random_bytes($length);
	$base32 = base32Encode($bytes);

	return substr($base32, 0, $length);
}

# Examples of usage (To move somewhere else)
/*
$randomSecret = generateRandomSecret();
echo "Random Secret Key: $randomSecret<br>\n";

$secret = 'K3OBZ7XPR6T4PTNXSNCQ';
$enteredCode = '123456';
$digits = 6;
if (isset($_GET['c']) && isset($_GET['s'])) {
	$enteredCode = $_GET['c'];
	$secret = $_GET['s'];
	$isCodeValid = verifyTOTP($secret, $digits, $enteredCode);

	if ($isCodeValid) {
		echo "Code $enteredCode is valid!<br>";
	} else {
		echo "Code $enteredCode is invalid!<br>";
	}
}

$code = generateTOTP($secret);
echo "TOTP code: $code<br>\n";

$code = generateTOTP($secret, 8);
echo "TOTP code: $code<br>\n";
*/
