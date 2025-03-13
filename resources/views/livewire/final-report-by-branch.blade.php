<div>
    <div class="p-4">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">支店別最終レポート</h1>
            <div class="flex gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">日付範囲</label>
                    <input
                            type="text"
                            wire:model.live="dateRange"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            x-data
                            x-init="flatpickr($el, {mode: 'range', dateFormat: 'Y-m-d'})"
                            placeholder="日付を選択"/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">ユーザー</label>
                    <select wire:model.live="userId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">すべてのユーザー</option>
                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日付</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">担当者</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">R新規</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">R継続</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RGH施工</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">フィルター</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ケア</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">アクション</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $report->report_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $report->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $report->r_new_count }}件 / {{ $report->r_new_volume }}本 / {{ number_format($report->r_new_amount) }}円
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $report->r_continue_count }}件 / {{ $report->r_continue_volume }}本 / {{ number_format($report->r_continue_amount) }}円
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $report->rgh_count }}件 / {{ number_format($report->rgh_amount) }}円
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $report->filter_volume }}本 / {{ number_format($report->filter_amount) }}円
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $report->care_count }}件 / {{ $report->care_volume }}本 / {{ number_format($report->care_amount) }}円
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">詳細</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">レポートが見つかりません</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                <tr class="font-bold">
                    <td colspan="2" class="px-6 py-3 text-right">合計:</td>
                    <td class="px-6 py-3">
                        {{ $totalStats->total_r_new_count }}件 / {{ $totalStats->total_r_new_volume }}本 / {{ number_format($totalStats->total_r_new_amount) }}円
                    </td>
                    <td class="px-6 py-3">
                        {{ $totalStats->total_r_continue_count }}件 / {{ $totalStats->total_r_continue_volume }}本 / {{ number_format($totalStats->total_r_continue_amount) }}円
                    </td>
                    <td class="px-6 py-3">
                        {{ $totalStats->total_rgh_count }}件 / {{ number_format($totalStats->total_rgh_amount) }}円
                    </td>
                    <td class="px-6 py-3">
                        {{ $totalStats->total_filter_volume }}本 / {{ number_format($totalStats->total_filter_amount) }}円
                    </td>
                    <td class="px-6 py-3">
                        {{ $totalStats->total_care_count }}件 / {{ $totalStats->total_care_volume }}本 / {{ number_format($totalStats->total_care_amount) }}円
                    </td>
                    <td class="px-6 py-3"></td>
                </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
