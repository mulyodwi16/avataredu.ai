<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4">
    <h2 class="text-3xl font-bold text-center mb-2">What People Say</h2>
    <p class="text-gray-600 text-center mb-12">Join thousands of satisfied learners</p>

    @php
      $testimonials = [
        ['name' => 'Sarah Chen', 'role' => 'Product Designer', 'img' => 'https://randomuser.me/api/portraits/women/44.jpg', 'text' => 'The UI/UX course was incredibly practical. I use the techniques every day at work.'],
        ['name' => 'Ahmad Rahman', 'role' => 'Data Analyst', 'img' => 'https://randomuser.me/api/portraits/men/32.jpg', 'text' => 'Prompt Engineering helped me 10× my productivity with AI tools. Highly recommended!'],
        ['name' => 'Priya Sharma', 'role' => 'Software Developer', 'img' => 'https://randomuser.me/api/portraits/women/68.jpg', 'text' => 'The Python course was perfect for beginners. Clear and practical.'],
      ];
    @endphp

    <div class="grid md:grid-cols-3 gap-8">
      @foreach($testimonials as $t)
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
          <div class="flex gap-0.5 mb-4">
            @for($i = 0; $i < 5; $i++)
              <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            @endfor
          </div>

          <p class="text-gray-700 italic mb-6">"{{ $t['text'] }}"</p>

          <div class="flex items-center gap-3">
            <img src="{{ $t['img'] }}" alt="{{ $t['name'] }}" class="w-10 h-10 rounded-full object-cover">
            <div>
              <p class="font-semibold">{{ $t['name'] }}</p>
              <p class="text-sm text-gray-500">{{ $t['role'] }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>