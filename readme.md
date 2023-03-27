# MainWP Notify Scripts

Scripts designed to help notify of important information from a MainWP Dashboard on macOS.

## Requirements

* [MainWP](https://mainwp.com/) Dashboard with REST API turned on
* PHP
* [Terminal Notifier](https://github.com/julienXX/terminal-notifier)

## Configuration

1. Copy `config-sample.json` to `config.json` and enter your values from your site's REST API page.
2. Set up how you will run the desired scripts automatically

## Scripts

The following scripts can be run to check for data on your MainWP dashboard:

* `check-for-mainwp-updates.php`: reports on available updates the dashboard sees and notifies if there are any

More inevitably to come...

## Launch Agents

The ideal way to run these scripts is via `launchd`. Example plist files to be installed in `~/Library/LaunchAgents` can be found in `launchd-examples`.