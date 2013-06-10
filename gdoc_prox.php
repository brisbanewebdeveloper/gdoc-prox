<?php

Class gdoc_prox {

    public $baseUrl = 'your_base_url';

    public function buildQuery($query_add = array()) {
        $query = array(
            /**
             * Setting this to zero becomes no cache. Setting this to 1 to check the change for Google Doc
             * that you have just updated (though, you can just delete the cache)
             */
            'x' => '3600',
            /**
             * This is optional and if you send this, cache will be valid for next X seconds
             * and that gets extended for the X seconds every time the document is referred
             */
            'r' => '3600',
            /**
             * You do not touch the followings unless you know what you are doing
             *
             * c: Client ID
             * s: Client secret
             * t: Refresh Token
             */
            'c' => 'your_client_id',
            's' => 'your_secret',
            't' => 'your_refresh_token',
        );
        $query = array_merge($query, $query_add);
        return http_build_query($query);
    }
    /**
     * Gets individual Google Doc
     *
     * The difference from "show" method is the return value is as JSON
     *
     * @param $docId String Document ID - Get it with getList method
     * @param $options Array x: Expired time (sec), r: Remained time (sec)
     *
     * Parameter "x" is used when you initially get Google Doc data or cache is expired.
     * Cache is going to be expired for the next "x" seconds.
     *
     * Parameter "r" is used when you get Google Doc data from second time.
     * Cache is going to be expired for the next "r" seconds
     * every time you get Google Doc.
     * Parameter "r" is optional.
     *
     * Following example gets you Google Doc every time your site/service
     * gets the document data.
     *
     * Sample:
     * $gdoc_prox->get($docId, array(
     *     'x' => '1',
     *     'r' => '1',
     * ));
     */
    public function get($docId, $options = array()) {
        if ( ! isset($options['query'])) $options['query'] = array();
        $query = $this->buildQuery(array_merge($options['query'], array('id' => $docId)));
        return json_decode(file_get_contents($this->baseUrl . '/get/?' . $query));
    }
    /**
     * Gets individual Google Doc
     *
     * The difference from "get" method is the return value is not in JSON
     *
     * Sample:
     * $gdoc_prox->show($docId, array(
     *     'h' => 'h2',
     * ));
     */
    public function show($docId, $options = array()) {
        if ( ! isset($options['query'])) $options['query'] = array();
        $query = $this->buildQuery(array_merge($options['query'], array('id' => $docId)));
        return file_get_contents($this->baseUrl . '/show/?' . $query);
    }
    /**
     * Gets list of Google Docs
     */
    public function getList($options = array()) {
        if ( ! isset($options['query'])) $options['query'] = array();
        $query = $this->buildQuery($options['query']);
        return json_decode(file_get_contents($this->baseUrl . '/list/?' . $query));
    }
    /**
     * Gets list of cached Google Docs
     *
     * Notice: You may see expired Google Docs
     *         because Google Doc Proxy currently does not delete expired cache data.
     *         Therefore, the purpose of using this method is only for deleteDocument method.
     */
    public function getCachedDocuments($options = array()) {
        if ( ! isset($options['query'])) $options['query'] = array();
        $query = $this->buildQuery($options['query']);
        return json_decode(file_get_contents($this->baseUrl . '/list-cached-documents/?' . $query));
    }
    /**
     * Deletes cache data for individual Google Doc
     */
    public function deleteDocument($docId, $options = array()) {
        if ( ! isset($options['query'])) $options['query'] = array();
        $query = $this->buildQuery(array_merge($options['query'], array('id' => $docId)));
        return json_decode(file_get_contents($this->baseUrl . '/delete-document/?' . $query));
    }
    /**
     * Deletes all the cache data for individual Google Doc
     * Deletes the list data of Google Docs
     */
    public function deleteData($options = array()) {
        if ( ! isset($options['query'])) $options['query'] = array();
        $query = $this->buildQuery($options['query']);
        return json_decode(file_get_contents($this->baseUrl . '/delete-all/?' . $query));
    }
}
