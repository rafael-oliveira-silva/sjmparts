<?php
/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 27/11/17
 * Time: 15:11
 */

use Cielo_Api_CieloEcommerce as CieloEcommerce;

class Trezo_Cielo_Model_Cielo_CardTransaction extends Trezo_Cielo_Model_Cielo_AbstractTransaction
{

    public function getResponseTransaction()
    {
        // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
        $request = new CieloEcommerce($this->merchant, $this->environment);
        $return = $request->createSale(self::$sale);
        $this->logRequest(json_encode(self::$sale));
        $this->logResponse(json_encode($return));

        return $return;
    }

    /**
     * Brands cielo: Visa / Master / Amex / Elo / Aura / JCB / Diners / Discover/ Hipercard
     * @param  string $cardType CC TYPE Magento
     * @return string|null          CC TYPE CIELO
     */
    protected function getCieloCardType($cardType)
    {
        switch ($cardType) {
            case 'VI':
                return 'Visa';
                break;
            case 'MC':
                return 'Master';
                break;
            case 'AE':
                return 'Amex';
                break;
            case 'DN':
                return 'Diners';
                break;
            case 'EL':
                return 'Elo';
                break;
            case 'DC':
                return 'Discover';
                break;
            case 'JC':
                return 'JCB';
                break;
            case 'HI':
                return 'Hipercard';
                break;
            case 'AU':
                return 'Aura';
                break;
        }
    }

}