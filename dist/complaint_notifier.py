import time
import requests
from win10toast import ToastNotifier
from win10toast_click import ToastNotifier
import webbrowser


# Initialize the ToastNotifier
toaster = ToastNotifier()

# Function to open a URL
def open_url():
    webbrowser.open("https://website-cb99bbb9.pkh.sga.mybluehost.me/")

# Function to check for unread complaints from the API
def check_complaints():
    headers = {
        'Accept': 'application/json',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    }
    response = requests.get('https://website-cb99bbb9.pkh.sga.mybluehost.me/api/complaints/count-unread', headers=headers)
    return response.json().get('count', 0)

# Main loop to periodically check the API and show notifications
def main():
    last_count = 0

    while True:
        try:
            unread_count = check_complaints()
            if unread_count > last_count:
                toaster.show_toast(
                    "شكاوي العملاء", f"شكوى جديدة لديك: {unread_count}",
                    duration=10,
                        icon_path=None,  # You can add an icon if you have one
                    threaded=True,
                    callback_on_click=open_url
                    )
                last_count = unread_count
                # Keep the script running until the notification disappears
                while toaster.notification_active():
                    pass
        except Exception as e:
            print(f"Error: {e}")
        time.sleep(60)  # Check every 120 seconds

if __name__ == "__main__":
    main()
