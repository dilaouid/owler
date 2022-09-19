<?php

function array_keys_exists(array $keys, array $arr) {
   return (!array_diff_key(array_flip($keys), $arr));
}