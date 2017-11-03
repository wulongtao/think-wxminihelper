<?php
namespace wlt\wxmini;

/**
 * Prpcrypt class
 *
 *
 */
class Prpcrypt
{
    public $key;


    function __construct( $k )
    {

        $this->key = $k;
    }

    /**
     * 对密文进行解密
     * @param string $aesCipher 需要解密的密文
     * @param string $aesIV 解密的初始向量
     * @return string 解密得到的明文
     */
    public function decrypt( $aesCipher, $aesIV )
    {

        try {

        /**
         * Mcrypt has been DEPRECATED as of PHP 7.1.0.
         * Replace with OpenSSL.（Support  PHP >= 5.3.0）
         * @see http://php.net/manual/zh/function.openssl-decrypt.php
         */
             $decrypted = openssl_decrypt($aesCipher, 'AES-128-CBC', $this->key, OPENSSL_RAW_DATA, $aesIV);
        } catch (\Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }


        try {
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);

        } catch (\Exception $e) {
            //print $e;
            return array(ErrorCode::$IllegalBuffer, null);
        }
        return array(0, $result);
    }
}
