<x-layout>

    <div class="max-w-6xl mx-auto my-10 p-6 bg-white shadow-lg rounded-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Past Notifications</h2>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100">
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
                    <td class="border p-2">{{ $notice->name }}</td>
                    <td class="border p-2">{{ $notice->description }}</td>
                    <td class="border p-2 font-semibold
                            @if($notice->notification_type_id == 'announcement') text-orange-600
                            @elseif($notice->notification_type_id == 'information') text-blue-600
                            @else text-red-600 @endif">
                        {{ ucfirst($notice->notification_type_id) }}
                    </td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($notice->expiry_date)->format('M d, Y h:i A') }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($notice->created_at)->format('M d, Y h:i A') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div class="max-w-6xl mx-auto my-10 p-6 bg-white shadow-lg rounded-md">


        <h2 class="text-xl font-semibold mb-4 text-gray-800">Add New Notification</h2>

        <form action="{{ route('admin.notices.store') }}" method="POST" class="space-y-4">
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
                    <option value="announcement">🟠 Announcement</option>
                    <option value="information">🔵 Information</option>
                    <option value="outage">🔴 Outage</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="datetime-local" name="expiry_date" required
                       class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                Publish Notification
            </button>
        </form>
    </div>
</x-layout>
