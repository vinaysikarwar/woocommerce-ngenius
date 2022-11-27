<?php

/**
 * NgeniusGatewayHttpRefund class.
 */
class NgeniusGatewayHttpRefund extends NgeniusGatewayHttpAbstract
{

    public function get_captured_amount($response)
    {
        $captured_amt = 0;
        $embeded      = self::NGENIUS_EMBEDED;
        $capture_stmt = self::NGENIUS_CAPTURE;
        if (isset($response->$embeded->$capture_stmt)) {
            foreach ($response->$embeded->$capture_stmt as $capture) {
                if (isset($capture->state) && ('SUCCESS' === $capture->state) && isset($capture->amount->value)) {
                    $captured_amt += $capture->amount->value;
                }
            }
        }

        return $captured_amt;
    }

    public function get_refunded_amount($response)
    {
        $refunded_amt    = 0;
        $data            = array();
        $transaction_arr = array();
        $embeded         = self::NGENIUS_EMBEDED;
        $refund_stmt     = self::NGENIUS_REFUND;
        $cmp_refund      = 'cnp:refund';
        if (isset($response->$embeded->$refund_stmt)) {
            $transaction_arr = end($response->$embeded->$cmp_refund);
            foreach ($response->$embeded->$refund_stmt as $refund) {
                if (isset($refund->state) && ('SUCCESS' === $refund->state) && isset($refund->amount->value)) {
                    $refunded_amt += $refund->amount->value;
                }
            }
        }

        $last_refunded_amt = 0;
        if (isset($transaction_arr->state) && ('SUCCESS' === $transaction_arr->state) && isset($transaction_arr->amount->value)) {
            $last_refunded_amt = $transaction_arr->amount->value / 100;
        }

        $data['refunded_amount']   = $refunded_amt;
        $data['transaction_arr']   = $transaction_arr;
        $data['last_refunded_amt'] = $last_refunded_amt;

        return $data;
    }

    public function get_transaction_id($transaction_arr)
    {
        if (isset($transaction_arr->_links->self->href)) {
            $transaction_arr = explode('/', $transaction_arr->_links->self->href);

            return end($transaction_arr);
        }
    }

    public function get_order_status($captured_amt, $refunded_amt)
    {
        if ($captured_amt === $refunded_amt) {
            $order_status = 'refunded';
        } else {
            $order_status = substr($this->order_status[6]['status'], 3);
        }

        return $order_status;
    }

    /**
     * Processing of API request body
     *
     * @param array $data
     *
     * @return string
     */
    protected function pre_process(array $data)
    {
        return json_encode($data);
    }

    /**
     * Processing of API response
     *
     * @param array $response_enc
     *
     * @return array|null
     */
    protected function post_process($response)
    {
        if (isset($response->errors)) {
           return [
               'result' => []
               ];
        } else {
            $refunded_data     = $this->get_refunded_amount($response);
            $transaction_arr   = $refunded_data['transaction_arr'];
            $refunded_amt      = $refunded_data['refunded_amount'];
            $last_refunded_amt = $refunded_data['last_refunded_amt'];
            $captured_amt = $this->get_captured_amount($response);

            $transaction_id = $this->get_transaction_id($transaction_arr);

            $state = isset($response->state) ? $response->state : '';

            $order_status = $this->get_order_status($captured_amt, $refunded_amt);

            return [
                'result' => [
                    'captured_amt'   => ($captured_amt - $refunded_amt) / 100,
                    'refunded_amt'   => $last_refunded_amt,
                    'state'          => $state,
                    'order_status'   => $order_status,
                    'transaction_id' => $transaction_id,
                    'total_refunded_amount' => $refunded_amt,
                ],
            ];
        }
    }

}
