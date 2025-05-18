<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body>


    <div class="aside is-placed-left is-expanded bg-black-erie">
        <div class="aside-tools bg-black">
            <div>
                <a href="{{ route('dashboard') }}" class="flex justify-center items-center p-2 h-16">
                    <img class="w-12" src={{ asset('build/assets/images/BossUpgrade_logo.jpg') }} alt="bossupgrade_logo">
                    <p class="appname font-bungee text-white font-poppins_bold font-bol">{{ config('app.name') }}</p>
                </a>
            </div>
        </div>
        @include('shared.nav_sidebar')
    </div>

    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <ul>
                <li>Admin</li>
                <li>Menu Items</li>
            </ul>
        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')

        <div class="card flex">
            <a class="button bg-blue-50 font-poppins_regular p-2 self-end" href="{{ route('add-menu-items') }}">Add Menu</a>
        </div>
        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-burger"></i></span>
                    Items
                </p>
                <a href="{{ route('archive.index') }}" class="card-header-icon bg-blue-200 rounded-sm">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-gray-600">Name</th>
                            <th class="text-gray-600">Image</th>
                            <th class="text-gray-600">Description</th>
                            <th class="text-gray-600">Price</th>
                            <th class="text-gray-600">Created</th>
                            <th class="text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $item)
                        <tr class="hover:cursor-pointer">
                            <td class="p-3 text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item['name'] ?? 'null' }}</td>
                            <td class="image-cell">
                                <div>
                                    <img src="{{ is_array($item['imageUrl']) ? $item['imageUrl'][0] : ($item['imageUrl'] ?? 'default_image_path') }}" class="rounded-sm w-12 h-12 object-cover" alt="{{ $item['name'] }}">
                                </div>
                            </td>
                            <td class="truncate max-w-sm">{{ $item['menuDescription'] ?? 'null' }}</td>
                            <td>
                                <small>₱ {{number_format($item['price'] ?? 'null', 2) }}</small>
                            </td>
                            <td class="text-gray-500">
                                <small>{{ $item['time_added'] ?? 'null' }}</small>
                            </td>
                            <td class="actions-cell">
                                <div class="buttons right nowrap">
                                    <!-- <button class="button small blue --jb-modal" data-target="view-modal-{{ $key }}" type="button">
                                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                                    </button> -->
                                    <a href="{{ route('menu-items.edit', $item['menuId']) }}" class="button small green">
                                        <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                    </a>

                                    <!-- <button class="button small red --jb-modal" data-target="archive-modal{{ $item['menuId'] }}" type="button">
                                        <span class="icon"><i class="mdi mdi-package-down"></i></span>
                                    </button> -->
                                    <button class="button small red --jb-modal" data-target="archive-modal{{$item['menuId']}}" type="button">
                                        <span class="icon"><i class="mdi mdi-archive-arrow-down"></i></span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div id="archive-modal{{ $item['menuId'] }}" class="modal hidden">
                            <div class="modal-background --jb-modal-close"></div>
                            <div class="modal-card">
                                <header class="modal-card-head">
                                    <i class="mdi mdi-archive-arrow-down text-red-500 mr-4"></i>
                                    <p class="modal-card-title text-black">Confirm <span class="text-red-500">Archiving</span></p>
                                </header>
                                <section class="modal-card-body text-gray-700">
                                    <p>Are you sure you want to <span class="text-red-500">archive</span> this item? Once archived, this item will be hidden from users and <strong>won't be visible on mobile users</strong>.</p>
                                </section>
                                <footer class="modal-card-foot justify-end">
                                    <button id="cancel-archive" class="button --jb-modal-close">Cancel</button>
                                    <form id="archiveForm" action="{{ route('menu-items.archive', $item['menuId']) }}" method="POST" class="inline-block ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="confirm-archive" class="button red archive-confirm">Confirm</button>
                                    </form>
                                </footer>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4  text-center">No menu items available.</td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination">
                    @if (count($data) > 0)
                    <div class="w-full mt-5 ">{{ $data->links('vendor.pagination.tailwind') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>


    <div id="sample-modal" class="modal">
        <div class="modal-background --jb-modal-close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title text-black">Sample modal</p>
            </header>
            <section class="modal-card-body text-gray-700">
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </section>
            <footer class="modal-card-foot justify-end">
                <button class="button --jb-modal-close">Cancel</button>
                <button class="button red --jb-modal-close">Confirm</button>
            </footer>
        </div>
    </div>

    <div id="sample-modal-2" class="modal">
        <div class="modal-background --jb-modal-close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title text-black">Sample modal</p>
            </header>
            <section class="modal-card-body text-gray-700">
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </section>
            <footer class="modal-card-foot justify-end">
                <button class="button --jb-modal-close">Cancel</button>
                <button class="button blue --jb-modal-close">Confirm</button>
            </footer>
        </div>
    </div>

    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <script src="../../js/archive.js"></script>
</body>

</html>