<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Credit
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Credit extends \Opencart\System\Engine\Model {
	/**
	 * @param array<int, array<string, mixed>> $totals
	 * @param array<int, float>                $taxes
	 * @param float                            $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		$this->load->language('extension/opencart/total/credit');

		$balance = $this->customer->getBalance();

		if ((float)$balance) {
			$credit = min($balance, $total);

			if ((float)$credit > 0) {
				$totals[] = [
					'extension'  => 'opencart',
					'code'       => 'credit',
					'title'      => $this->language->get('text_credit'),
					'value'      => -$credit,
					'sort_order' => (int)$this->config->get('total_credit_sort_order')
				];

				$total -= $credit;
			}
		}
	}

	/**
	 * @param array<string, mixed> $order_info
	 * @param array<string, mixed> $order_total
	 *
	 * @return void
	 */
	public function confirm(array $order_info, array $order_total): void {
		$this->load->language('extension/opencart/total/credit');

		if ($order_info['customer_id']) {
			$this->load->model('account/transaction');

			$this->model_account_transaction->addTransaction($order_info['customer_id'], sprintf($this->language->get('text_order_id'), (int)$order_info['order_id']), $order_total['value'], $order_info['order_id']);
		}
	}

	/**
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function unconfirm(int $order_id): void {
		$this->load->model('account/transaction');


		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");
	}
}
