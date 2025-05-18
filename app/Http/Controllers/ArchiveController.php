<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;
    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }
    public function paginatedData(Request $request)
    {
        $database = $this->firebaseService->getDatabase();

        // Retrieve the menu and category data from Firebase
        $menuData = $database->getReference('archives/menu')->getValue() ?? [];  // Assign an empty array if null
        $categoriesSnapshot = $database->getReference('categories')->getValue();

        $selectedCategoryId = $request->input('category');

        // Filter the menu data by category if a category is selected
        if ($selectedCategoryId && $categoriesSnapshot) {
            $filteredMenu = [];

            if (isset($categoriesSnapshot[$selectedCategoryId]['menuIds'])) {
                foreach ($categoriesSnapshot[$selectedCategoryId]['menuIds'] as $menuId => $value) {
                    if (isset($menuData[$menuId])) {
                        $filteredMenu[$menuId] = $menuData[$menuId];
                    }
                }
            }
            $menuData = $filteredMenu;
        }

        // Build the category list for the dropdown
        $categories = [];
        if ($categoriesSnapshot) {
            foreach ($categoriesSnapshot as $categoryId => $categoryData) {
                if (isset($categoryData['categoryName'])) {
                    $categories[] = [
                        'id' => $categoryId,
                        'name' => $categoryData['categoryName']
                    ];
                }
            }
        }

        // Sort the menu data by time_added if it's not empty
        if (!empty($menuData)) {
            usort($menuData, function ($a, $b) {
                return $b['time_added'] <=> $a['time_added'];
            });
        }

        // Paginate the data
        $collection = collect($menuData);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedData = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('archive', [
            'data' => $paginatedData,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryId
        ]);
    }


    public function destroy($menuId)
    {

        try {
            $database = $this->firebaseService->getDatabase();
            $database->getReference('archives/menu/' . $menuId)->remove();

            return redirect()->route('archive.index')->with('success', 'Menu Item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('archive.index')->with('error', 'Failed to Category.');
        }
    }

    public function restore($menuId)
    {
        try {
            $database = $this->firebaseService->getDatabase();

            // Step 1: Get archived item
            $archivedData = $database->getReference('archives/menu/' . $menuId)->getValue();

            if (!$archivedData) {
                return redirect()->route('archive.index')->with('error', 'Menu item not found in archive.');
            }

            // Step 2: Restore it to the main menu path
            $database->getReference('menu/' . $menuId)->set($archivedData);

            // Step 3: Remove it from archive
            $database->getReference('archives/menu/' . $menuId)->remove();

            return redirect()->route('archive.index')->with('success', 'Menu Item restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('archive.index')->with('error', 'Failed to restore menu item.');
        }
    }
}
