<?php
namespace ElasticSearch;

class LinkBuilder {
    /**
     * @param array $base
     * @param mixed $key
     * @param mixed$value
     * @return string
     */
    public function httpToggleKey(array $base, $key, $value) {
		return $this->httpBuild($this->toggleKey($base, $key, $value));
    }

    /**
     * @param array $base
     * @param mixed $key
     * @param mixed $value
     * @return string
     */
    public function httpToggleValue (array $base, $key, $value) {
        return $this->httpBuild($this->toggleValue($base, $key, $value));
    }

    /**
     * @param array $base
     * @param mixed $key
     * @param mixed $value
     * @return array
     */
    private function toggleKey(array $base, $key, $value) {
        if(array_key_exists($key, $base)) {
            unset($base[$key]);
        } else {
            $base[$key] = $value;
        }
        return $base;
    }

    /**
     * @param array $base
     * @param mixed $key
     * @param mixed $value
     * @return array
     */
    private function toggleValue(array $base, $key, $value) {
        if(array_key_exists($key, $base) && is_array($base[$key]) && in_array($value, $base[$key])) {
            unset($base[$key][array_search($value, $base[$key])]);
        } else {
            if(!array_key_exists($key, $base) || !is_array($base[$key])) {
                $base[$key] = array();
            }
            $base[$key][] = $value;
        }
        return $base;
    }

    /**
     * @param array $data
     * @return string
     */
    private function httpBuild(array $data) {
        return http_build_query($data);
    }
}
