<x-layout>
<div class="flex space-x-12 items-start">
    <div class="max-w-6xl ml-auto my-10 p-6 bg-white shadow-lg rounded-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Past Notices</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100">
                <th class="border p-2 text-gray-700">No.</th>
                <th class="border p-2 text-gray-700">Title</th>
                <th class="border p-2 text-gray-700">Description</th>
                <th class="border p-2 text-gray-700">Type</th>
                <th class="border p-2 text-gray-700">Expiry Date</th>
                <th class="border p-2 text-gray-700">Created At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($notices as $notice)
                <tr class="text-gray-700">
                    <td class="border p-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $notice->name }}</td>
                    <td class="border p-2">{{ $notice->description }}</td>
                    <td class="border p-2 font-semibold
                    @php echo $notice->notificationType ? 'text-' . $notice->notificationType->color . '-600' : 'text-gray-600'; @endphp">
                        {{ $notice->notificationType ? ucfirst($notice->notificationType->type) : 'Unknown' }}
                    </td>
                    <td class="border p-2">
                        @if($notice->expiry_date != null)
                            {{ \Carbon\Carbon::parse($notice->expiry_date)->format('M d, Y h:i A') }}
                        @else
                        No Expiry
                        @endif
                    </td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($notice->created_at)->format('M d, Y h:i A') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div class="max-w-6xl mr-auto p-6 my-10 bg-white shadow-lg rounded-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Add New Notice</h2>

        <form action="{{ route('admin.notice.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Notice Title</label>
                <input type="text" name="name" required
                       class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" required
                          class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notice Type</label>
                <select name="notification_type_id" required
                        class="w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="1">🟠 Announcement</option>
                    <option value="2">🔵 Information</option>
                    <option value="3">🔴 Outage</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="datetime-local" name="expiry_date"
                       class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                Publish Notice
            </button>
        </form>
    </div>
</div>
</x-layout>
