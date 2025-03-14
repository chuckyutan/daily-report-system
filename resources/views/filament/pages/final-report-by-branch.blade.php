<x-filament-panels::page>
    <div class="mb-6">
        <x-filament::input.wrapper>
            <x-filament::input.select wire:model.live="branchId">
                <option value="0">すべての支店</option>
                @foreach($this->branchs as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </x-filament::input.select>
        </x-filament::input.wrapper>
    </div>

    {{ $this->table }}
</x-filament-panels::page>