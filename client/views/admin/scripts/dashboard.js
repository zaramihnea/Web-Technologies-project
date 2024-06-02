async function handleDeleteButton(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        try {
            const response = await fetch('delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ user_id: userId }),
            });

            if (response.ok) {
                const result = await response.text();
                if (result === 'success') {
                    alert('User deleted successfully');
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    alert('Failed to delete user');
                }
            } else {
                alert('Failed to delete user');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to delete user');
        }
    }
  }

  async function handleBanButton(userId) {
    if (confirm('Are you sure you want to ban this user?')) {
        try {
            const response = await fetch('ban_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ user_id: userId }),
            });

            if (response.ok) {
                const result = await response.text();
                if (result === 'success') {
                    alert('User baned successfully');
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    alert('Failed to ban user');
                }
            } else {
                alert('Failed to ban user');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to ban user');
        }
    }
  }