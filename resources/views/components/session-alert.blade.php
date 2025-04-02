@props(['status' => session('status'), 'message' => session('message')])

@if($status && $message)
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4">
    <div @class([
        'p-4 rounded-lg border',
        'bg-blue-50 border-blue-200 text-blue-800' => $status === 'info',
        'bg-green-50 border-green-200 text-green-800' => $status === 'success',
        'bg-yellow-50 border-yellow-200 text-yellow-800' => $status === 'warning',
        'bg-red-50 border-red-200 text-red-800' => $status === 'error',
    ])>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($status === 'success')
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">...</svg>
                @elseif($status === 'error')
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">...</svg>
                @elseif($status === 'warning')
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">...</svg>
                @else
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">...</svg>
                @endif
                <span>{{ $message }}</span>
            </div>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">...</svg>
            </button>
        </div>
    </div>
</div>
@endif