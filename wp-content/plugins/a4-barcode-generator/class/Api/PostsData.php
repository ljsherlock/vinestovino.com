<?php

namespace UkrSolution\ProductLabelsPrinting\Api;

use UkrSolution\ProductLabelsPrinting\Models\PostsUtils;
use UkrSolution\ProductLabelsPrinting\Request;
use UkrSolution\ProductLabelsPrinting\Validator;

class PostsData
{
    public function getIdsBulkList()
    {
        Request::ajaxRequestAccess();
        $validationRules = array('list' => 'array', 'raw' => 'complexCodeValue');
        $post = array();
        $result = array();

        if (isset($_POST['list'])) {
            $post['list'] = USWBG_a4bRecursiveSanitizeTextField($_POST['list']);
        }
        if (isset($_POST['raw'])) {
            $post['raw'] = sanitize_textarea_field($_POST['raw']);
        }

        $data = Validator::create($post, $validationRules, true)->validate();

        update_user_meta(get_current_user_id(), 'a4b_bulk_list_raw', $data['raw']);

        $postsUtils = new PostsUtils();
        foreach ($data['list'] as &$postField) {
            unset($postField['success']);
            $result[] = $postsUtils->getPostIdByField($postField);
        }

        uswbg_a4bJsonResponse($result);
    }
}
