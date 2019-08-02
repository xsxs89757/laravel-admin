<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Dictionary;

class DictionaryController extends Controller
{
	/**
	 * 字典列表
	 * @Author   lei.wang
	 * @DateTime 2019-08-02T09:30:51+0800
	 * @return   Json
	 */
	protected function list()
	{
		$list = Dictionary::select('id','name','title')->orderBy('created_at','desc')->get();
		return responseJson($list);
	}
	/**
	 * 字典详情
	 * @Author   lei.wang
	 * @DateTime 2019-08-02T09:35:20+0800
	 * @param    Int                   $id [字典id]
	 * @return   Json                  
	 */
	protected function detail($name)
	{
		$value = Dictionary::where('name', $name)->value('value');
		$value = $value?unserialize($value):[];
		return responseJson($value);
	}

	/**
	 * 添加字典
	 * @Author   lei,wang
	 * @DateTime 2019-08-01T18:35:18+0800
	 * @param    Request                  $request
	 * @return   Json
	 */
    protected function add(Request $request)
    {
    	$rules = [
            'name'   => 'required',
            'title' => 'required'
        ];
        $this->validate($request, $rules); //验证输入
    	$attributes = ['name'=>$request->input('name'),'title'=>$request->input('title')];
    	$dictionary = Dictionary::createById($attributes);
        if($dictionary){
            return responseJson($dictionary);
        }else{
            throw new \App\Exceptions\Admin\CustomException(21002);
        }
    	
    }

    /**
	 * 编辑字典
	 * @Author   lei.wang
	 * @DateTime 2019-08-01T18:35:18+0800
	 * @param    Request                  $request
	 * @return   Json
	 */
    protected function edit(Request $request)
    {
    	$rules = [
    		'id' => 'required|numeric',
            'name'   => 'required',
            'title' => 'required'
        ];
        $this->validate($request, $rules); //验证输入
    	$attributes = ['id'=>$request->input('id'),'name'=>$request->input('name'),'title'=>$request->input('title')];
    	$dictionary = Dictionary::saveById($attributes);
        if($dictionary){
            return responseJson(['message'=>trans('form.editSuccess')]);
        }else{
            throw new \App\Exceptions\Admin\CustomException(21003);
        }
    	
    }

    /**
	 * 保存字典
	 * @Author   lei.wang
	 * @DateTime 2019-08-01T18:35:18+0800
	 * @param    Request                  $request
	 * @return   Json
	 */
	protected function save(Request $request)
	{
		$rules = [
            'name'   => 'required'
        ];
        $this->validate($request, $rules); //验证输入
        $dictionary = Dictionary::where('name',$request->input('name'))->update(['value'=>serialize($request->input('listDetail'))]);
        if($dictionary){
            return responseJson(['message'=>trans('form.editSuccess')]);
        }else{
            throw new \App\Exceptions\Admin\CustomException(21003);
        }
	}


    /**
     * 删除字典
     * @Author   lei.wang
     * @DateTime 2019-08-02T10:39:51+0800
     * @param    Int                           $id
     * @return   Json
     */
    protected function delete($id)
    {
    	Dictionary::destroy($id);
        return responseJson([]);
    }

}
