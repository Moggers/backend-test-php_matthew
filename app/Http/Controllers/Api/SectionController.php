<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Http\Transformers\SectionTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Manager;


class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index()
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new FractalCollection(
                Section::all(),
                new SectionTransformer()
            )
        )->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Section $section
     *
     * @return Section
     */
    public function show(Section $section)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $section,
                new SectionTransformer()
            )
        )->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @todo Only moderators should be able to create sections
     *
     * @return Section
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, [
            'name' => 'required',
            ]
        );

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                Section::create($request->all()),
                new SectionTransformer()
            )
        )->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Section      $section
     *
     * @return Section
     */
    public function update(Request $request, Section $section)
    {
        $section->update($request->all());

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData(
            new Item(
                $section->fresh(),
                new SectionTransformer()
            )
        )->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Section $section
     *
     * @throws \Exception
     */
    public function destroy(Section $section)
    {
        $section->delete();
    }
}
