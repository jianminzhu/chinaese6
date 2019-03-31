<?php
require_once 'rb.php';
function save($tableName, $data)
{
    try {
        $to = R::dispense($tableName);
        foreach ($data as $key => $value) {
            $to[$key] = $value;
        }
        return R::store($to);
    } catch (Exception $e) {
        print "\nerror in " . $e->getMessage();
        return null;
    }
}

function findSave($tablename, $data)
{
    try {
        return R::findOrCreate($tablename, $data);
    } catch (Exception $e) {
        print "error in " . $e->getMessage();
        return null;
    }
}


