<?php

namespace App\Http\Controllers;


use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function fetch(Request $request)
    {

        try {
            $id = $request->input('id');
            $name = $request->input('name');
            $limit = $request->input('limit', 10);
            $teamQuery = Team::query();

            // Get Single Data 
            if ($id) {
                $team = $teamQuery->find($id);

                if ($team) {
                    return ResponseFormatter::success($team, 'Team Found');
                }
            }
            // Get Multi Data
            $teams = $teamQuery->where('company_id', $request->company_id);

            if ($name) {
                $team->where('name', 'like', '%' . $name . '%');
            }

            // return Response
            return ResponseFormatter::success(
                $teams->paginate($limit),
                'Teams Found'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function create(CreateTeamRequest $request)
    {
        try {
            //  Upload Icon
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icons');
            }
            //  Create Team
            $team = Team::create([
                'name' =>  $request->name,
                'icon' =>  $path,
            ]);

            if (!$team) {
                throw new Exception(' Team not found');
            }
            //  response succes 
            return ResponseFormatter::success($team, 'Team Created');
        } catch (\Throwable $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateTeamRequest $request, $id)
    {
        try {
            // get team
            $team = Team::find($id);

            // check if team exist
            if (!$team) {
                throw new Exception('Team not found');
            }

            // upload icon
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icon');
            }
            // update icon
            $team->update([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);
            // return response success
            return ResponseFormatter::success($team, 'Team updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // get team
            $team = Team::find($id);

            // check if team exist
            if (!$team) {
                throw new Exception('Team not found');
            }

            // detele team  
            $team->delete();

            // response
            return ResponseFormatter::success('Team deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
