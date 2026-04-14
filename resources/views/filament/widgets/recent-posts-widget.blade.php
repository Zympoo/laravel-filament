<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold">Recente posts</h2>
                <p class="text-sm text-gray-500">
                    De laatst aangemaakte posts in het systeem.
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse ($this->getRecentPosts() as $post)
                <div class="flex items-center justify-between rounded-xl border border-gray-200 p-4">
                    <div>
                        <p class="text-sm font-medium">
                            {{ $post->title }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Auteur: {{ $post->user?->name ?? 'Onbekend' }}
                        </p>
                    </div>

                    <div class="text-right">
                        <div class="mb-1">
                            @if ($post->is_published)
                                <span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                    Gepubliceerd
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                    Draft
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500">
                            {{ $post->created_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                    Er zijn nog geen posts beschikbaar.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
