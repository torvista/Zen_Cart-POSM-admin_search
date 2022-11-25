<?php

declare(strict_types=1);
/**
 * add POSM-managed products models to admin search
 *
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: torvista 2022 Nov 25
 *
 * TODO: rework the code that builds the sql query in category_product_listing to allow 100% observer modificationb
 */
/**
 * Class zcObserverPluginPosmAdminSearch
 */
class zcObserverPluginPosmAdminSearch extends base
{
    public function __construct()
    {
        $this->attach($this, [
            'NOTIFY_ADMIN_PROD_LISTING_PRODUCTS_QUERY', //modifies multiple additions to the query
        ]);
    }

// $p1 ... not set
// $p2 ... Additional SELECT fields
// $p3 ... Additional tables to FROM clause
// $p4 ... Additional table JOINS
// $p5 ... Additions to the WHERE clause
// $p6 ... Additions to the ORDER_BY clause
// The SELECT is built after this notifier, adding the extra fields and joins. That is straightfoward.
// However, the WHERE is constructed using a function zen_build_keyword_where_clause with no vanilla notifier available: have to add the search fields to $keyword_search_fields in category_product_listing

    protected function notify_admin_prod_listing_products_query(&$class, $eventID, $p1, &$p2, &$p3, &$p4, &$p5, &$p6): void
    {
        $search_result = isset($_GET['search']) && zen_not_null($_GET['search']);
        $action = $_GET['action'] ?? '';
        if ($search_result && $action !== 'edit_category') {
            $p4 = " LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_STOCK . " posm ON (posm.products_id = p.products_id)";
        }
    }
}
