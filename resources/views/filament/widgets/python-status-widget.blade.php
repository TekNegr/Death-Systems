<div>
    <span 
        x-data="{ status: '{{ $status }}' }" 
        x-init="
            setInterval(() => {
                $wire.refreshStatus();
            }, 5000);
        "
        :class="{
            'text-green-600': status === 'online',
            'text-red-600': status === 'offline',
            'text-gray-600': status === 'unknown',
        }"
        class="font-bold"
    >
        Python Service Status: <span x-text="status"></span>
    </span>
</div>
