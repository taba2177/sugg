import time
import requests
from plyer import notification
import win32serviceutil
import win32service
import win32event

class ComplaintNotifierService(win32serviceutil.ServiceFramework):
    _svc_name_ = "ComplaintNotifierService"
    _svc_display_name_ = "Complaint Notifier Service"
    _svc_description_ = "Checks for unread complaints and notifies the user via Windows notifications."

    def __init__(self, args):
        win32serviceutil.ServiceFramework.__init__(self, args)
        self.hWaitStop = win32event.CreateEvent(None, 0, 0, None)
        self.stop_requested = False

    def SvcStop(self):
        self.ReportServiceStatus(win32service.SERVICE_STOP_PENDING)
        win32event.SetEvent(self.hWaitStop)
        self.stop_requested = True

    def SvcDoRun(self):
        last_count = 0

        while not self.stop_requested:
            try:
                response = requests.get('http://your-laravel-app-url/api/complaints/count-unread')
                data = response.json()

                if data['count'] > last_count:
                    notification.notify(
                        title="New Complaints",
                        message=f"You have {data['count']} unread complaints.",
                        timeout=10  # Duration of the notification
                    )
                    last_count = data['count']

            except Exception as e:
                print(f"Error: {e}")

            time.sleep(60)  # Check every 60 seconds

if __name__ == '__main__':
    win32serviceutil.HandleCommandLine(ComplaintNotifierService)
