<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Character;

use Seat\Services\Repositories\Character\Intel;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\IntelNote;
use Seat\Web\Validation\NewIntelNote;
use Yajra\Datatables\Datatables;

/**
 * Class IntelController
 * @package Seat\Web\Http\Controllers\Character
 */
class IntelController extends Controller
{

    use Intel;

    /**
     * @var int
     */
    protected $top_limit = 10;

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIntelSummary(int $character_id)
    {

        return view('web::character.intel.summary');
    }

    /**
     * @param int $character_id
     *
     * @return
     */
    public function getTopWalletJournalData(int $character_id)
    {

        $top = $this->characterTopWalletJournalInteractions($character_id);

        return Datatables::of($top)
            ->editColumn('characterName', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return
     */
    public function getTopTransactionsData(int $character_id)
    {

        $top = $this->characterTopWalletTransactionInteractions($character_id);

        return Datatables::of($top)
            ->editColumn('characterName', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return
     */
    public function getTopMailFromData(int $character_id)
    {

        $top = $this->characterTopMailInteractions($character_id);

        return Datatables::of($top)
            ->editColumn('characterName', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);
    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandingsComparison(int $character_id)
    {

        $profiles = $this->standingsProfiles();

        return view('web::character.intel.standingscompare', compact('profiles'));
    }

    /**
     * @param int $character_id
     * @param int $profile_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCompareStandingsWithProfileData(int $character_id, int $profile_id)
    {

        $journal = $this->getCharacterJournalStandingsWithProfile($character_id, $profile_id);

        return Datatables::of($journal)
            ->editColumn('characterName', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNotes(int $character_id)
    {

        return view('web::character.intel.notes');

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     */
    public function getNotesData(int $character_id)
    {

        return Datatables::of(IntelNote::where('character_id', $character_id))
            ->addColumn('actions', function ($row) {

                return view('web::character.intel.partials.notesactions', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     * @param int $note_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleNotesData(int $character_id, int $note_id)
    {

        return response()->json(IntelNote::where('character_id', $character_id)
            ->where('id', $note_id)
            ->get());

    }

    /**
     * @param \Seat\Web\Validation\NewIntelNote $request
     * @param int                               $character_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddNew(NewIntelNote $request, int $character_id)
    {

        IntelNote::create([
            'character_id' => $character_id,
            'title'        => $request->input('title'),
            'note'         => $request->input('note'),
        ]);

        return redirect()->back()
            ->with('success', 'Note Added');

    }

    /**
     * @param int $character_id
     * @param int $note_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteNote(int $character_id, int $note_id)
    {

        IntelNote::where('character_id', $character_id)
            ->where('id', $note_id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Note deleted!');

    }

}
