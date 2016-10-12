<?php

/*
* ENCRYPT function returns X = M^E (mod N)
*
* Please check http://www.ge.kochi-ct.ac.jp/cgi-bin-takagi/calcmodp
* and Flash5 RSA .fla by R.Vijay <rveejay0@hotmail.com> at
* http://www.digitalillusion.co.in/lab/rsaencyp.htm
* It is one of the simplest examples for binary RSA calculations 
*
* Each letter in the message is represented as its ASCII code number - 30
* $char_per_block letters in each block.
* For example string AAA will become 353535 (A = ASCII 65-30 = 35)
* 
* block number is smaller then (smallest prime)^2
* This means that 
* 1. Modulo N will always be < 19999991
* 2. Letters > ASCII 128 must not occur in plain text message
*
* if you change to smaller prime numbers, you have to adjust the $char_per_block
*/

class RSA_CryptoClass {

	var $char_per_block;
	var $blocksize;
	var $split_char;

	function RSA_CryptoClass() {
		$this->char_per_block = 5;
		$this->blocksize = $this->char_per_block * 2;
		$this->split_char = "A";
	}

	function rsa_encrypt($m, $e, $n) {
		$asci = array ();
		for ($i=0; $i<strlen($m); $i+=$this->char_per_block) {
			$tmpasci="";
			for ($h=0; $h<$this->char_per_block; $h++) {
				if ($i+$h <strlen($m)) {
					$tmpstr = ord (substr ($m, $i+$h, 1)) - 30;
					//$tmpstr = ord (substr ($m, $i+$h, 1));
					if (strlen($tmpstr) < 2) {
						$tmpstr ="0".$tmpstr;
					}
				} else {
					break;
				}
				$tmpasci .=$tmpstr;
			}
			array_push($asci, $tmpasci."");
		}

		//Each number is then encrypted using the RSA formula: block ^E mod N
		$coded = "";
		for ($k=0; $k< count ($asci); $k++) {
			$resultmod = $this->powmod($asci[$k], $e, $n);
			$coded .= $resultmod.$this->split_char;
		}
		return trim($coded);
	}

	/*
	ENCRYPT function returns
	M = X^D (mod N)
	*/
	function rsa_decrypt($c, $d, $n) {
		//Strip the blank spaces from the ecrypted text and store it in an array
		$decryptarray = split($this->split_char, $c);
		for ($u=0; $u<count ($decryptarray); $u++) {
			if ($decryptarray[$u] == "") {
				array_splice($decryptarray, $u, 1);
			}
		}
		//Each number is then decrypted using the RSA formula: block ^D mod N
		$deencrypt = "";
		for ($u=0; $u< count($decryptarray); $u++) {
			$resultmod = $this->powmod($decryptarray[$u], $d, $n);
			// PHP alternative
			// $resultmod = bcpowmod($decryptarray[$u] , $d , $n, 0);
			// if (strlen($resultmod) == $this->blocksize-1) {
			if (strlen($resultmod)%2 > 0) {
				$resultmod = "0".$resultmod;
			}
			// remove leading and trailing '1' digits
			// $deencrypt.= substr ($resultmod,1,strlen($resultmod)-2);
			$deencrypt.= $resultmod;
		}
		//Each ASCII code number + 30 in the message is represented as its letter
		$resultd = "";
		for ($u=0; $u<strlen($deencrypt); $u+=2) {
			$resultd .= chr(substr ($deencrypt, $u, 2) + 30);
			// $resultd .= chr(substr ($deencrypt, $u, 2));
		}
		return $resultd;
	}

	/*Russian Peasant method for exponentiation */
	function powmod($base, $exp, $modulus) {
		$basepow2 = $base;
		$exppow2 = $exp;
		$retvalue = 0;

		if (1 == bcmod($exppow2, 2)) {
			$retvalue = bcmod(bcadd($retvalue, $basepow2), $modulus);
		}
		do {
			$basepow2 = bcmod(bcmul($basepow2, $basepow2), $modulus);
			$exppow2 = bcdiv($exppow2, 2);
			if (1 == bcmod($exppow2, 2)) {
				$retvalue = bcmod(bcmul($retvalue, $basepow2), $modulus);
			}
		} while (1 == bccomp($exppow2, 0));
		$retvalue = bcmod($retvalue, $modulus);
		return $retvalue;
	}

} //end class

// sample
$_REQUEST['sso3pauth'] = '591040430279A1105628859888A674217798868A640524348005A933312486746A516730104646A1001242490846A413302038341A820953842201A123200192515A80426583528';

// incoming request: http://www.baselli.ch?sso3pauth=591040430279A1105628859888A674217798868A640524348005A933312486746A516730104646A1001242490846A413302038341A820953842201A123200192515A80426583528
if (isset($_REQUEST['sso3pauth'])) {
	$sso_public_key = "1259803";
	$sso_private_key = "272587255723";
	$sso_rsa_modulo = "1131626881807";
	$message_delimiter = "#";
	$ocsso_c = new RSA_CryptoClass();
	$decoded_message = $ocsso_c->rsa_decrypt($_REQUEST['sso3pauth'], $sso_private_key, $sso_rsa_modulo);
	$parameter = explode($message_delimiter, $decoded_message);

	if (count($parameter) == 4) {
		if ((base64_decode($parameter[0]) == 'opc_baselli') && (intval(base64_decode($parameter[1])) > time()-86400) && (intval(base64_decode($parameter[1])) < time()+86400)) {
			/*
			 * TODO - Authentication lookup, opticoach client number lookup
			 * base64_decode($parameter[2]) => opticoach number
			 * base64_decode($parameter[3]) => redirect
			 */
		}

	}
}

?>
