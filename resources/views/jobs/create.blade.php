<x-layout>
    <form action="/jobs" method="POST">
        @csrf
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" class="bg-white border px-1 rounded-xs" placeholder="Web Developer">
            @error('title')
                <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
            @enderror
        </div>

        <div>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" class="bg-white border px-1 rounded-xs" placeholder="Enter job description">
            @error('description')
            <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
            @enderror
        </div>

        <button type="submit" class="ml-2 bg-white p-1 border rounded-lg">Submit</button>
    </form>
</x-layout>
