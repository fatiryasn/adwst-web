@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="{{ $active ? 'bg-secondary/10 text-secondary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} 
          flex items-center px-3 py-2 font-medium rounded-r-lg transition-colors">
    {{ $slot }}
</a>