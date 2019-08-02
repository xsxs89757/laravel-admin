<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
	//重置表名被自动添加复数S的方法
    public function getTable(){
        return $this->table?$this->table:strtolower(snake_case(class_basename($this)));
    }

    /**
     * 批量数据更新
     * @Author   lei.wang
     * @DateTime 2019-06-05T14:24:10+0800
     * @param    String                   $table        表名
     * @param    array                    $multipleData 更新字段
     * @return   int 					  $       		更新数量
     */
    public static function updateBatch(String $table,array $multipleData = [])
	{
		try {
			if (empty($multipleData)) {
				throw new \Exception("数据不能为空");
			}
			$tableName = DB::getTablePrefix() . $table; // 表名
			$firstRow = current($multipleData);

			$updateColumn = array_keys($firstRow);
			// 默认以id为条件更新，如果没有ID则以第一个字段为条件
			$referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
			unset($updateColumn[0]);
			// 拼接sql语句
			$updateSql = "UPDATE " . $tableName . " SET ";
			$sets  = [];
			$bindings = [];
			foreach ($updateColumn as $uColumn) {
			$setSql = "`" . $uColumn . "` = CASE ";
			foreach ($multipleData as $data) {
			$setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
			$bindings[] = $data[$referenceColumn];
			$bindings[] = $data[$uColumn];
			}
			$setSql .= "ELSE `" . $uColumn . "` END ";
			$sets[] = $setSql;
			}
			$updateSql .= implode(', ', $sets);
			$whereIn = collect($multipleData)->pluck($referenceColumn)->values()->all();
			$bindings = array_merge($bindings, $whereIn);
			$whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
			$updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
			return DB::update($updateSql, $bindings);
		} catch (\Exception $e) {
			return false;
		}
	}
}
