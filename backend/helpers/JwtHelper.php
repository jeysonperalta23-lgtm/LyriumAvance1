<?php
//backend\helpers\JwtHelper.php
class JwtHelper {
    private static $clave = "secreta123";

    public static function crearToken($data) {
        $header = json_encode(["alg" => "HS256", "typ" => "JWT"]);
        $payload = json_encode($data);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$clave, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function verificarToken($token) {
        $partes = explode('.', $token);
        if (count($partes) !== 3) return false;

        [$header, $payload, $firma] = $partes;
        $firmaVerificada = base64_encode(hash_hmac('sha256', "$header.$payload", self::$clave, true));

        return $firma === str_replace(['+', '/', '='], ['-', '_', ''], $firmaVerificada);
    }
}
