<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Facades\CauserResolver;

class LocalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-localization');

        return response()->json(Language::all());
    }
    public function localizationPagination()
    {
        $this->authorize('view-localization');

        return response()->json(Language::paginate(request()->segment(count(request()->segments()))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validateLang = Validator::make($request->all(), [
            'lang_locale' => 'required|max:3',
        ]);

        if ($validateLang->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateLang->errors(),
            ], 401);
        }
        $this->authorize('create-localization');
        
        CauserResolver::setCauser(auth()->user());
        Language::create([
            'lang_locale' => $request->lang_locale,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Language Created Successfully',
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        $this->authorize('view-localization');

        return response()->json($language);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {

        $validateLang = Validator::make($request->all(), [
            'lang_locale' => 'required|max:3',
        ]);

        if ($validateLang->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateLang->errors(),
            ], 401);
        }
        $this->authorize('edit-localization');


        CauserResolver::setCauser(auth()->user());
        $language->update([
            'lang_locale' => $request->lang_locale,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Language Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        $this->authorize('delete-localization');

        CauserResolver::setCauser(auth()->user());
        $language->delete();
        return response()->json([
            'status' => true,
            'message' => 'Language Deleted Successfully',
        ], 200);
    }
}
