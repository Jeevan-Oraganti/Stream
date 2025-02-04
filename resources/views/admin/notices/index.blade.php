
    <form action="{{ route('admin.notices.store') }}" method="POST">
        @csrf
        <input type="text" name="name" required>
        <textarea name="description" required></textarea>
        <select name="notification_type_id">
            <option value="announcement">Announcement</option>
            <option value="information">Information</option>
            <option value="outage">Outage</option>
        </select>
        <input type="datetime-local" name="expiry_date" required>
        <button type="submit">Add Notice</button>
    </form>
