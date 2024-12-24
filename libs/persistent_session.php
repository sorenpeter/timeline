<?php
$config = parse_ini_file('private/config.ini');

# TODO: Move this verification to another file
$required_keys = ['secret_key', 'password', 'totp_secret', 'totp_digits', 'secure_cookies'];
$missing_keys = array_filter($required_keys, fn($key) => !isset($config[$key]));

if (!empty($missing_keys)) {
	die('Missing required keys in config.ini: ' . implode(', ', $missing_keys));
}

# To make it more secure, something like JWT could be used instead

const COOKIE_NAME = 'timeline_login';
const ENCRYPTION_METHOD = 'aes-256-cbc';
const EXPIRATION_DAYS = 30;

session_start([
	'name' => 'timeline_session',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	'cookie_secure' => $config['secure_cookies'],
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	'cookie_samesite' => 'Strict', # Not compatible with PHP < 7.3
]);

function hasValidSession(): bool|string {
	# If short lived session is valid
	if (isset($_SESSION['valid_session'])) {
		return true;
	}

	# Otherwise, check the persistent cookie
	return isSavedCookieValid();
}

function encrypt(string $data, string $key, string $method): string {
	$ivSize = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivSize);
	$encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
	$encrypted = strtoupper(implode(unpack('H*', $encrypted)));

	return $encrypted;
}

function decrypt(string $data, string $key, string $method): string | bool {
	$data = pack('H*', $data);
	$ivSize = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($ivSize);
	$decrypted = openssl_decrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

	var_dump($decrypted);

	if ($decrypted === false) {
		return false;
	}

	return trim($decrypted);
}

function saveLoginSuccess() {
	$_SESSION['valid_session'] = true;

	$config = parse_ini_file('private/config.ini');

	# Set a cookie to remember the user
	$cookieExpiry = EXPIRATION_DAYS * 24 * 60 * 60 + time();
	$encodedCookieValue = generateCookieValue(strval($cookieExpiry), $config['secret_key']);

	setcookie(COOKIE_NAME, $encodedCookieValue, [
		'expires' => $cookieExpiry,
		'secure' => $config['secure_cookies'],
		'httponly' => true,
		'samesite' => 'Strict',
	]);
}

function generateCookieValue($value, $secretKey) {
	$key = bin2hex($secretKey);

	$encrypted = encrypt($value, $key, ENCRYPTION_METHOD);
	return $encrypted;
}

function isSavedCookieValid() {
	if (!isset($_COOKIE[COOKIE_NAME])) {
		return false;
	}

	$config = parse_ini_file('private/config.ini');

	$encoded_cookie_value = $_COOKIE[COOKIE_NAME];
	$key = bin2hex($config['secret_key']);

	$cookieVal = decrypt($encoded_cookie_value, $key, ENCRYPTION_METHOD);

	if ($cookieVal === false) {
		deletePersistentCookie();
		return false;
	}

	# TODO: Check that the cookie is not expired

	saveLoginSuccess(); # Extend expiracy for previous cookie

	return true; # If it was decoded correctly, it's a valid session
}

function deletePersistentCookie() {
	if (isset($_COOKIE[COOKIE_NAME])) {
		unset($_COOKIE[COOKIE_NAME]);
		setcookie(COOKIE_NAME, '', time() - 3600);
	}
}
