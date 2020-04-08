<?php
class DDE_Utilities_Model_Cron
{
    const SUCCESSFUL_DELIVERY_EVENTS = ['BDE/00', 'BDI/00', 'BDR/00', 'BDE/01', 'BDI/01', 'BDR/01'];

    public function changeOrderStatus()
    {
        $trackTable = 'main_table';
        $orderTable = Mage::getModel('sales/order')->getCollection()->getResource()->getTable('sales/order');

        $collection = Mage::getModel('sales/order_shipment_track')->getCollection();
        $collection->getSelect()->join($orderTable, "{$trackTable}.order_id = {$orderTable}.entity_id", array());
        $collection
            ->addFieldToFilter("{$trackTable}.carrier_code", 'pedroteixeira_correios')
            ->addFieldToFilter("{$orderTable}.state", Mage_Sales_Model_Order::STATE_COMPLETE)
            ->addFieldToFilter("{$orderTable}.status", ['neq' => 'entregue']);
        
        $collection->setOrder("{$trackTable}.order_id", 'desc');
        
        /** @var Mage_Sales_Model_Order_Shipment_Track $track */
        foreach ($collection as $track) {
            /** @var Mage_Sales_Model_Order_Shipment $shipment */
            $shipment = $track->getShipment();
            
            /** @var Mage_Sales_Model_Order_Shipment_Comment $comment */
            foreach ($shipment->getCommentsCollection() as $comment) {
                $event = $this->parseEvent($comment->getComment());
                $hasEvent = !empty($event);
                if (!$hasEvent) {
                    continue;
                }

                if (!$this->hasDeliverySucceded($event)) {
                    continue;
                }

                /** @var Mage_Sales_Model_Order $order */
                $order = Mage::getModel('sales/order')->load($track->getOrderId());
                $order->setStatus('entregue');
                $order->save();
                
                break;
            }
        }
    }
    
    private function parseEvent(string $comment)
    {
        $hasEvent = strstr($comment, 'Evento: ');
        
        if (!$hasEvent) {
            return '';
        }

        $text = trim(end(explode('Evento:', $comment)));

        return $text;
    }
    
    private function hasDeliverySucceded($event)
    {
        return in_array($event, self::SUCCESSFUL_DELIVERY_EVENTS);
    }
}
