<?php

function awpcp_fees_collection() {
    return new AWPCP_FeesCollection();
}

class AWPCP_FeesCollection {

    public function all() {
        return AWPCP_Fee::query();
    }
}
