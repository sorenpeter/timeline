<?php
$config = parse_ini_file('private/config.ini');

# TODO: Move this verification to another file
$required_keys = ['secret_key', 'password', 'totp_secret', 'totp_digits', 'secure_cookies'];
$missing_keys = array_filter($required_keys, fn($key) => !isset($config[$key]));

if (!empty($missing_keys)) {
	die('Missing required keys in config.ini: ' . implode(', ', $missing_keys));
}

if (strlen($config['secret_key']) < 32) {
	die('Secret key in config.ini must be at least 32 characters long');
}

const COOKIE_NAME = 'timeline_login';
const ENCRYPTION_METHOD = 'aes-256-cbc';
const EXPIRATION_DAYS = 30;

const HASH_LENGTH = 128;
const HASH_ALGORITHM = 'sha512';

session_start([
	'name' => 'timeline_session',
	'use_strict_mode' => true,
	'cookie_httponly' => true,
	'cookie_secure' => $config['secure_cookies'],
	'sid_length' => 64,
	'sid_bits_per_character' => 6,
	'cookie_samesite' => 'Strict', # Not compatible with PHP < 7.3
]);

function hasValidSession(): bool {
	# If short lived session is valid
	if (isset($_SESSION['session_expiration']) && $_SESSION['session_expiration'] > time()) {
		return true;
	}

	# TODO: Check if the session has expired
	# Add more protection to prevent session fixation
	# https://owasp.org/www-community/attacks/Session_fixation

	# Otherwise, check the persistent cookie
	return isSavedCookieValid();
}

function getCookieData() {
	if (!isset($_COOKIE[COOKIE_NAME])) {
		#echo "Cookie " . COOKIE_NAME . " not found";
		return false;
	}

	$raw = base64_decode($_COOKIE[COOKIE_NAME]);
	#var_dump($raw);

	# Cookie should be at least the size of the hash length.
	# If it's not, we can just bail out
	if (strlen($raw) < HASH_LENGTH) {
		#echo "Didn't get minimum length";
		return false;
	}

	$config = parse_ini_file('private/config.ini');

	# The cookie data contains the actual data w/ the hash concatenated to the end,
	# since the hash is a fixed length, we can extract the last hash_length chars
	# to get the hash.
	$hash = substr($raw, strlen($raw) - HASH_LENGTH, HASH_LENGTH);
	$data = substr($raw, 0, - (HASH_LENGTH));

	# Calculate the expected hash from the data. If the data has not been
	# tampered with, $hash and $hash_calculated will be the same
	$hash_calculated = hash_hmac(HASH_ALGORITHM, $data, $config['secret_key']);

	if ($hash_calculated !== $hash) {
		#echo "Different HASH. Tempered data?";
		return False;
	}

	if (intval($data) < time()) {
		#echo "Cookie expired";
		return False;
	}

	return $data;
}

function makePersistentCookie() {
	$config = parse_ini_file('private/config.ini');

	$cookieExpiry = EXPIRATION_DAYS * 24 * 60 * 60 + time(); # X days
	#$cookieExpiry = 10 + time(); # Debug value - 10 seconds

	# Calculate a hash for the data and append it to the end of the data string
	$cookieValue = strval($cookieExpiry);

	$hash = hash_hmac(HASH_ALGORITHM, $cookieValue, $config['secret_key']);
	$cookieValue .= $hash;
	$cookieValue = base64_encode($cookieValue);

	# Also create the short-timed session
	$_SESSION['session_expiration'] = $cookieExpiry;

	return setcookie(COOKIE_NAME, $cookieValue, [
		'expires' => $cookieExpiry,
		'secure' => $config['secure_cookies'],
		'httponly' => true,
		'samesite' => 'Strict',
	]);
}

function saveLogin() {
	makePersistentCookie();
}

function isSavedCookieValid() {
	$cookieExpiry = getCookieData();

	if ($cookieExpiry === false) {
		deletePersistentCookie();
		return false;
	}

	# @eapl As it's implemented, the user has to login again in 30 days
	# since the first login, which I think is a good compromise.

	# Refresh session
	$_SESSION['session_expiration'] = intval($cookieExpiry);

	return true;
}

function deletePersistentCookie() {
	if (isset($_COOKIE[COOKIE_NAME])) {
		unset($_COOKIE[COOKIE_NAME]);
		setcookie(COOKIE_NAME, '', time() - 3600);
	}
}
