<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold">
                    Drafts die nog aandacht vragen
                </h2>

                <p class="text-sm text-gray-500">
                    Niet-gepubliceerde posts die nog afgewerkt of nagekeken kunnen worden.
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse ($this->getDraftPosts() as $post)
                <div class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <div>
                        <p class="text-sm font-medium">
                            {{ $post->title }}
                        </p>

                        <p class="mt-1 text-xs text-gray-600">
                            Auteur: {{ $post->user?->name ?? 'Onbekend' }}
                        </p>
                    </div>

                    <div class="text-right">
                        <span class="inline-flex items-center rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">
                            Draft
                        </span>

                        <p class="mt-2 text-xs text-gray-500">
                            {{ $post->created_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                    Er zijn momenteel geen drafts.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
