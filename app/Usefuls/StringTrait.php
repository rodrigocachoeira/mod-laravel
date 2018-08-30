<?php

namespace App\Usefuls;

/**
 * Métodos auxiliares refernte a strings
 *
 * @author Rodrigo Cachoeira <rodrigocachoeira11@gmail.com>
 * @class StringTrait
 * @package App\Core\Usefuls
 */
trait StringTrait
{

    /***
     * Função para remover acentos de uma string
     *
     * @param $string
     * @return string
     */
    public function friendlyText($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }

    /**
     * Remove todos os espaços de uma string
     * e troca por underline
     *
     * @param $string
     * @return mixed
     */
    public function friendlyFolderName ($string)
    {
        return str_replace(' ', '_', $string);
    }

    /**
     * LImita a quantidae de caracteres
     * de uma string
     *
     * @param $string
     * @param $size
     * @return string
     */
    public function stringLimit ($string, $size)
    {
        if (strlen($string) <= $size)
            return $string;

        return substr($string, 0, $size).'...';
    }

    /**
     * Quando uma string possuir um uppercase
     * separa com underline
     *
     * @param $value
     * @return mixed
     */
    public function uppercaseToUnderline ($value)
    {
        return strtolower(preg_replace('/\B([A-Z])/', '_$1', $value));
    }

    /**
     * Converte um texto qualquer
     * para ele mesmo aplicando uma máscara
     * qualquer
     *
     * @param string $val
     * @param string $mask
     * @return string
     */
    public function toMask(string $val, string $mask)
    {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    /**
     * Retira a formação
     *
     * @param string $value
     * @return mixed
     */
    public function unmaskCnpjCPf (string $value)
    {
        return str_replace(',', '', str_replace('.', '', str_replace('-', '', str_replace('/', '', $value))));
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function moneyFormat(string $value)
    {
        return round((float)str_replace(',','.', str_replace('.','', $value)), 2);
    }

    /**
     * @param $value
     * @return string
     */
    public function presentableMoney($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
