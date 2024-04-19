<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicHotelPage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PublicHotelPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view-publichotelpage');

        return response()->json(PublicHotelPage::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
             $request->validate([
                'company_name'        => 'required|max:255',
                'company_name_loc'    => 'required|max:255',
                'hotel_name'          => 'required|max:255',
                'hotel_name_loc'      => 'required|max:255',
                'phone'               => 'nullable|numeric|unique:public_hotel_pages',
                'mobile'              => 'nullable|numeric|unique:public_hotel_pages',
                'email'               => 'nullable|email|unique:public_hotel_pages,email',
                'address'             => 'nullable',
                'address2'            => 'nullable',
                'description'         => 'nullable',
                'location'            => 'nullable',
                'booking_page'        => 'nullable',
                'facebook'            => 'nullable',
                'website'             => 'nullable',
                'CR_no'               => 'nullable',
                'vat_no'              => 'nullable',
                'logo'                => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'picture1'            => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'picture2'            => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'picture3'            => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            ]); 
            $this->authorize('create-publichotelpage');

                 if ($request->hasFile('logo')) {
                    $image = $request->file('logo');
                    $filename = 'logo'.time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/images/', $filename);
                }
                 if($request->hasFile('picture1')){
                    $image1 = $request->file('picture1');
                    $filename1 = 'img1'.time() . '.' . $image1->getClientOriginalExtension();
                    $image1->storeAs('public/images/', $filename1);   
                }
                 if($request->hasFile('picture2')){
                    $image2 = $request->file('picture2');
                    $filename2 = 'img2'.time() . '.' . $image2->getClientOriginalExtension();
                    $image2->storeAs('public/images/', $filename2);   
               }
                if($request->hasFile('picture3')){
                    $image3 = $request->file('picture3');
                    $filename3 = 'img3'.time() . '.' . $image3->getClientOriginalExtension();
                    $image3->storeAs('public/images/', $filename3);    
        }

     $publicHomePage=PublicHotelPage::create([

                                'company_name'                   =>$request->company_name,
                                'company_name_loc'               =>$request->company_name_loc,
                                'hotel_name'                     =>$request->hotel_name,
                                'hotel_name_loc'                 =>$request->hotel_name_loc,
                                'phone'                          =>$request->phone,
                                'mobile'                         =>$request->mobile,
                                'email'                          =>$request->email,
                                'address'                        =>$request->address,
                                'address2'                       =>$request->address2,
                                'description'                    =>$request->description,
                                'location'                       =>$request->location,
                                'booking_page'                   =>$request->booking_page,
                                'facebook'                       =>$request->facebook,
                                'website'                        =>$request->website,
                                'CR_no'                          =>$request->CR_no,
                                'vat_no'                         =>$request->vat_no,
                                ]);

                if (isset($filename)) {
                    $publicHomePage->logo = $filename;
                }
                if(isset($filename1)){
                    $publicHomePage->picture1 = $filename1;

                }
                if(isset($filename2)){
                    $publicHomePage->picture2 = $filename2;

                }
                if(isset($filename3)){
                    $publicHomePage->picture3 = $filename3;
                }
                $publicHomePage->save();

            return response()->json([
                'message'  => 'public HomePage Created Successfully',
                'data'     =>  $publicHomePage ,
                ], 201);

            }catch (ValidationException $e) {
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors'  => $e->errors(),
                    ], 400);     
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view-publichotelpage');

        $publicHomePageShow = PublicHotelPage::find($id);
        return response()->json($publicHomePageShow);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        try{
          // dd($request->company_name);
             $request->validate([
                'company_name'        => 'required|max:255',
                'company_name_loc'    => 'required|max:255',
                'hotel_name'          => 'required|max:255',
                'hotel_name_loc'      => 'required|max:255',
                'phone'               => 'nullable|numeric|unique:public_hotel_pages',
                'mobile'              => 'nullable|numeric|unique:public_hotel_pages',
                'email'               => 'nullable|email|unique:public_hotel_pages,email',
                'address'             => 'nullable',
                'address2'            => 'nullable',
                'description'         => 'nullable',
                'location'            => 'nullable',
                'booking_page'        => 'nullable',
                'facebook'            => 'nullable',
                'website'             => 'nullable',
                'CR_no'               => 'nullable',
                'vat_no'              => 'nullable',
                'logo'                => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'picture1'            => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'picture2'            => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                'picture3'            => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $this->authorize('edit-publichotelpage');

            $publicHomePageUpdate=PublicHotelPage::findOrFail($id);

            if ($request->hasFile('logo')) {
                
                Storage::delete('public/images/'.$publicHomePageUpdate->logo);
            }
            if ($request->hasFile('picture1')) {
              
                Storage::delete('public/images/'.$publicHomePageUpdate->picture1);
            }
            if ($request->hasFile('picture2')) {
              
                Storage::delete('public/images/'.$publicHomePageUpdate->picture2);
            }
            if ($request->hasFile('picture3')) {
                
                Storage::delete('public/images/'.$publicHomePageUpdate->picture3);
            }

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = 'logo'.time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
            }
             if($request->hasFile('picture1')){
                $image1 = $request->file('picture1');
                $filename1 = 'img1'.time() . '.' . $image1->getClientOriginalExtension();
                $image1->storeAs('public/images/', $filename1);   
            }
             if($request->hasFile('picture2')){
                $image2 = $request->file('picture2');
                $filename2 = 'img2'.time() . '.' . $image2->getClientOriginalExtension();
                $image2->storeAs('public/images/', $filename2);   
           }
            if($request->hasFile('picture3')){
                $image3 = $request->file('picture3');
                $filename3 = 'img3'.time() . '.' . $image3->getClientOriginalExtension();
                $image3->storeAs('public/images/', $filename3);    
    }
           

$publicHomePageUpdate=PublicHotelPage::where('id', $id)->update([
        'company_name'                   =>$request->company_name,
        'company_name_loc'               =>$request->company_name_loc,
        'hotel_name'                     =>$request->hotel_name,
        'hotel_name_loc'                 =>$request->hotel_name_loc,
        'phone'                          =>$request->phone,
        'mobile'                         =>$request->mobile,
        'email'                          =>$request->email,
        'address'                        =>$request->address,
        'address2'                       =>$request->address2,
        'description'                    =>$request->description,
        'location'                       =>$request->location,
        'booking_page'                   =>$request->booking_page,
        'facebook'                       =>$request->facebook,
        'website'                        =>$request->website,
        'CR_no'                          =>$request->CR_no,
        'vat_no'                         =>$request->vat_no,
        ]);

       
            return response()->json([
                            'message'  => 'public HomePage Updated Successfully',
                            'data'     =>  $publicHomePageUpdate ,
                            ], 201);

                }catch (ValidationException $e) {
                                return response()->json([
                                    'message' => 'Validation Error',
                                    'errors'  => $e->errors(),
                                ], 400);     
                }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
