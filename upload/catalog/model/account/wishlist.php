<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Wishlist
 *
 * @package Opencart\Catalog\Model\Account
 */
class Wishlist extends \Opencart\System\Engine\Model {
	/**
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function addWishlist(int $customer_id, int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "' AND `product_id` = '" . (int)$product_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_wishlist` SET `customer_id` = '" . (int)$customer_id . "', `product_id` = '" . (int)$product_id . "', `date_added` = NOW()");
	}

	/**
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function deleteWishlist(int $customer_id, int $product_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "'";

		if ($product_id) {
			$sql .= " AND `product_id` = '" . (int)$product_id . "'";
		}
		
		$this->db->query($sql);
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function getWishlist(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	/**
	 * @return int
	 */
	public function getTotalWishlist(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}
}
