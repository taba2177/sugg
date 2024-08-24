import requests
from win10toast import ToastNotifier

toaster = ToastNotifier()

def fetch_and_notify():
    try:
        headers = {
            'Accept': 'application/json',
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'}
        response = requests.get('https://website-cb99bbb9.pkh.sga.mybluehost.me/api/complaints/count-unread', headers=headers)


        if response.status_code == 200:
            data = response.json()
            if 'count' in data:
                toaster.show_toast(
                    "شكاوي العملاء",
                    f"شكوى جديدة {data['count']} لديك",
                    duration=10,
                    threaded=True
                )
                print("Notification sent")
        else:
            print(f"Received unexpected status code {response.content}")
    except Exception as e:
        print(f"Error: {e}")

fetch_and_notify()
