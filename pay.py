import platform
import os
import sys

def block_website(website_url):
    """
    Blocks a website by adding an entry to the hosts file,
    redirecting its domain to 127.0.0.1 (localhost).

    Args:
        website_url (str): The URL of the website to block (e.g., "www.example.com").
    """
    # Determine the hosts file path based on the operating system
    if platform.system() == "Windows":
        hosts_path = r"C:\Windows\System32\drivers\etc\hosts"
    else:  # Linux or macOS
        hosts_path = "/etc/hosts"

    redirect_ip = "192.192.2.23:8000"  # Redirect to localhost
    entry_to_add = f"{redirect_ip} {website_url}\n"

    try:
        # Check if the script is running with administrative/root privileges
        if not os.access(hosts_path, os.W_OK):
            print(f"Error: You need administrative/root privileges to modify '{hosts_path}'.")
            print("Please run this script as an administrator (Windows) or using 'sudo' (Linux/macOS).")
            sys.exit(1)

        with open(hosts_path, "a") as hosts_file:
            hosts_file.write(entry_to_add)
        print(f"Successfully blocked '{website_url}'. You should now see 'This site can't be reached'.")
        print("Remember to unblock it later using the 'unblock_website' function or manually edit your hosts file.")

    except Exception as e:
        print(f"An error occurred: {e}")

def unblock_website(website_url):
    """
    Unblocks a website by removing its entry from the hosts file.

    Args:
        website_url (str): The URL of the website to unblock.
    """
    # Determine the hosts file path based on the operating system
    if platform.system() == "Windows":
        hosts_path = r"C:\Windows\System32\drivers\etc\hosts"
    else:  # Linux or macOS
        hosts_path = "/etc/hosts"

    try:
        # Check if the script is running with administrative/root privileges
        if not os.access(hosts_path, os.W_OK):
            print(f"Error: You need administrative/root privileges to modify '{hosts_path}'.")
            print("Please run this script as an administrator (Windows) or using 'sudo' (Linux/macOS).")
            sys.exit(1)

        with open(hosts_path, "r") as hosts_file:
            lines = hosts_file.readlines()

        with open(hosts_path, "w") as hosts_file:
            for line in lines:
                if website_url not in line:
                    hosts_file.write(line)
        print(f"Successfully unblocked '{website_url}'.")

    except Exception as e:
        print(f"An error occurred: {e}")

if __name__ == "__main__":
    # Example Usage:
    # To block a website:
    # Make sure to run this script with administrative/root privileges!
    # On Windows: Right-click the script and select "Run as administrator".
    # On Linux/macOS: Run from terminal using 'sudo python your_script_name.py'

    website_to_block = input("Enter the website URL to block (e.g., www.facebook.com): ")
    block_website(website_to_block)

    # To unblock a website:
    # uncomment the following lines and run the script again
    # website_to_unblock = input("Enter the website URL to unblock: ")
    # unblock_website(website_to_unblock)

    print("\n--- Important ---")
    print("After blocking/unblocking, you might need to flush your DNS cache for changes to take effect.")
    print("  - Windows: Open Command Prompt as administrator and run 'ipconfig /flushdns'")
    print("  - macOS: Open Terminal and run 'sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder'")
    print("  - Linux: The command varies depending on your distribution and DNS resolver.")
    print("           Common commands include 'sudo systemctl restart NetworkManager' or 'sudo /etc/init.d/nscd restart'.")