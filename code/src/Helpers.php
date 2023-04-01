<?
/**
 * Summary of Helpers
 */
class Helpers {
    
    /**
     * Summary of classBaseName
     * @param object $class
     * @return string
     */
    public static function classBaseName(object | string $class): string {
        $class = is_object($class)? get_class($class): $class;
        return basename(str_replace('\\', '/', $class));
    }

    public static function map(array $array, callable $callback)
    {
        $keys = array_keys($array);

        try {
            $items = array_map($callback, $array, $keys);
        } catch (ArgumentCountError) {
            $items = array_map($callback, $array);
        }

        return array_combine($keys, $items);
    }

    public static function log(string $log){
        file_put_contents('/var/www/html/log_'.date("d_m_Y").'.log', $log."\n", FILE_APPEND);
    }
}