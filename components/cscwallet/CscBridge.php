<?php

namespace components\cscwallet;

use Yii;

class CscBridge
{
    public $vk;
    public $uk;

    public function __construct($params = [])
    {
        $this->vk = base64_decode($params['privateKey']);
        $this->uk = base64_decode($params['publicKey']);
    }

    public function set_key_values($spce9dfd, $spd2a3a9)
    {
        $this->vk = $spce9dfd;
        $this->uk = $spd2a3a9;
    }

    private function spdfa662()
    {
        return $this->uk;
    }

    private function sp9dae05()
    {
        return $this->vk;
    }

    private function spf55472($sp3979c4)
    {
        $sp07b7bb = substr($sp3979c4, strlen($sp3979c4) - 1, 1);
        $sp8a2502 = ord($sp07b7bb);
        if ($sp8a2502 > 0 && $sp8a2502 <= 16) {
            $spf6cf85 = strlen($sp3979c4) - $sp8a2502;
            $sp2b890a = substr($sp3979c4, 0, $spf6cf85);
            return $sp2b890a;
        } return $sp3979c4;
    }

    private function spc33aa2($sp2be4ff, $sp2dcbfd = 16)
    {
        $sp780a4b = $sp2dcbfd - strlen($sp2be4ff) % $sp2dcbfd;
        return $sp2be4ff . str_repeat(chr($sp780a4b), $sp780a4b);
    }

    private function spc21c55($spb60a3c = 'T!')
    {
        $spe6768d = '';
        $spfb57db = $this->sp9dae05();
        $spdbab3f = openssl_sign($spb60a3c, $spe6768d, $spfb57db, 'sha256');
        if ($spdbab3f) {
            return base64_encode($spe6768d);
        } return false;
    }

    private function sp7680df($sp110fa6, $spe6768d)
    {
        $sp1db2b6 = $this->spdfa662();
        $spbcc49c = openssl_verify($sp110fa6, $spe6768d, $sp1db2b6, 'sha256');
        return $spbcc49c === 1;
    }

    public function rsaEncrypt($spb60a3c = 'T!')
    {
        $sp1db2b6 = $this->spdfa662();
        $spb35edc = '';
        $spce362e = @openssl_public_encrypt($spb60a3c, $spb35edc, $sp1db2b6, OPENSSL_PKCS1_OAEP_PADDING);
        if ($spce362e) {
            return base64_encode($spb35edc);
        } return false;
    }

    public function rsaDecrypt($spec6a7b)
    {
        $sp110fa6 = @base64_decode($spec6a7b);
        $spb35edc = '';
        $spfb57db = $this->sp9dae05();
        $spce362e = @openssl_private_decrypt($sp110fa6, $spb35edc, $spfb57db, OPENSSL_PKCS1_OAEP_PADDING);
        if ($spce362e) {
            return base64_encode($spb35edc);
        } return false;
    }

    public function aesEncrypt($spb60a3c = 'T!', $sp821b1f = '0000000000 0000000000 0000000000')
    {
        $spddc8eb = '0000000000000000';
        $spa90095 = $this->spc33aa2($spb60a3c);
        $spb60a3c = $spa90095;
        $sp3979c4 = openssl_encrypt($spb60a3c, 'aes-256-cbc', $sp821b1f, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $spddc8eb);
        return base64_encode($sp3979c4);
    }

    public function aesDecrypt($sp8bd71c, $sp821b1f = '0000000000 0000000000 0000000000')
    {
        $spddc8eb = '0000000000000000';
        $sp3979c4 = @openssl_decrypt(base64_decode($sp8bd71c), 'aes-256-cbc', $sp821b1f, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $spddc8eb);
        $sp2434a3 = $this->spf55472($sp3979c4);
        $sp3979c4 = $sp2434a3;
        return base64_encode($sp3979c4);
    }

    private function spf9357c($sp298d66, $spb6959f)
    {
        $sp539d78 = base64_decode($spb6959f) . '' . base64_decode($sp298d66);
        return base64_encode($sp539d78);
    }

    private function sp737c6e($spfcde41)
    {
        $spaf8d6a = base64_decode($spfcde41);
        $sp84cd10 = substr($spaf8d6a, 128, strlen($spaf8d6a) - 128);
        return base64_encode(substr(base64_decode($spfcde41), 0, 128));
    }

    private function sp88dee2($spfcde41)
    {
        $spaf8d6a = base64_decode($spfcde41);
        $sp84cd10 = substr($spaf8d6a, 128, strlen($spaf8d6a) - 128);
        return base64_encode($sp84cd10);
    }

    public function encrypt_message_for_wallet($sp6db873, $sp3b2a58 = TRUE)
    {
        $spe8ac9e = '';
        $spbfc941 = $this->spc21c55($sp6db873);
        $sp1ae5ad = $spbfc941;
        if ($sp3b2a58) {
            $sp1ae5ad = rawurlencode($spbfc941);
        } $sp824b62 = $sp6db873 . 'checksum=' . $sp1ae5ad;
        $spfd702b = $this->sp4a72ec();
        $sp1f885d = $this->aesEncrypt($sp824b62, $spfd702b);
        $spa92e3b = $this->rsaEncrypt($spfd702b);
        $spe8ac9e = $this->spf9357c($sp1f885d, $spa92e3b);
        if ($sp3b2a58) {
            $spe8ac9e = rawurlencode($spe8ac9e);
        } return $spe8ac9e;
    }

    public function decrypt_wallet_message($spe8ac9e, &$sp6db873, $sp3b2a58 = TRUE, $sp596ed9 = TRUE)
    {
        $spab6db8 = $spe8ac9e;
        if ($sp3b2a58) {
            $spab6db8 = rawurldecode(urldecode($spab6db8));
        } $spa38cdb = $this->sp737c6e($spab6db8);
        $sp932caa = $this->sp88dee2($spab6db8);
        $sp7d4655 = $this->rsaDecrypt($spa38cdb);
        $spe0463e = $this->aesDecrypt($sp932caa, base64_decode($sp7d4655));
        $sp824b62 = base64_decode($spe0463e);
        $sp1bef92 = 'checksum=';
        $sp6db873 = substr($sp824b62, 0, strpos($sp824b62, $sp1bef92));
        $sp883a3b = false;
        if ($sp596ed9) {
            $sp7b92ee = substr($sp824b62, strpos($sp824b62, $sp1bef92) + strlen($sp1bef92));
            if ($sp3b2a58) {
                $sp7b92ee = rawurldecode(urldecode($sp7b92ee));
            } $sp883a3b = $this->sp7680df($sp6db873, base64_decode($sp7b92ee));
        } return $sp883a3b;
    }

    private function sp34b74f($sp3d873f, $sp7243df)
    {
        $spce86a7 = $sp7243df - $sp3d873f;
        if ($spce86a7 < 1) {
            return $sp3d873f;
        } $spc6a63e = ceil(log($spce86a7, 2));
        $sp78cf05 = (int) ($spc6a63e / 8) + 1;
        $sp29b352 = (int) $spc6a63e + 1;
        $spb898f7 = (int) (1 << $sp29b352) - 1;
        do {
            $sp5df74e = hexdec(bin2hex(openssl_random_pseudo_bytes($sp78cf05)));
            $sp5df74e = $sp5df74e & $spb898f7;
        }
        while ($sp5df74e >= $spce86a7);
        return $sp3d873f + $sp5df74e;
    }

    private function sp4a72ec()
    {
        return $this->spcb8683(128 / 4);
    }

    private function spcb8683($spb9a1d7)
    {
        $sp800609 = '';
        $spb7fbed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $sp7243df = strlen($spb7fbed) - 1;
        for ($spcfb84e = 0; $spcfb84e < $spb9a1d7; $spcfb84e++) {
            $sp800609 .= $spb7fbed[$this->sp34b74f(0, $sp7243df)];
        } return $sp800609;
    }

    public function ping()
    {
        echo 'PONG!!';
    }

}
