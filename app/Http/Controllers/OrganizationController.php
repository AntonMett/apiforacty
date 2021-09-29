<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_name' => 'required|filled',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        $org_name = $request->org_name;
        $org_id = Organization::query()->where('org_name', $org_name)->value('id');
        $parent_id = Relation::query()->where('org_name', $request->org_name)->value('parent_id');
        $sisters = Relation::query()->where('parent_id', $parent_id)->where('org_name', '!=', $org_name)->select('org_name')->get();
        $daughters = Relation::query()->where('parent_id', $org_id)->select('org_name')->get();
        $parents = Organization::query()->where('id', $parent_id)->select('org_name')->get();
        return [['sisters' => $sisters, 'parents' => $parents, 'daughters' => $daughters]];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'org_name' => 'required|filled',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $neworganization = new Organization();
        $neworganization->org_name = $request->org_name;
        $neworganization->save();
        if (isset($request->parent_of)) {
            foreach ($request->parent_of as $org) {
                $newrelation = new Relation();
                $newrelation->org_name = $org;
                $newrelation->parent_id = $neworganization->id;
                $newrelation->save();
                $organization = new Organization();
                $organization->org_name = $org;
                $organization->save();

            }
            $daughters = Relation::query()->where('parent_id', $neworganization->id)->select('org_name')->get();
            return ['org_name' => $neworganization->org_name, 'daughters' => $daughters];
        }

        return ['org_name' => $neworganization->org_name];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
