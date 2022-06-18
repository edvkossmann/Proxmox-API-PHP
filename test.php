<?php
# Load Curl API
include_once("api.php");

# Access Data for PVE Node
$pve_server	= "192.168.99.11"; # Hostname or IP
$pve_port	= "8006"; # PVE Port
$pve_user	= "root@pam"; # PVE Username
$pve_pass	= "XXXXXXXXXXX"; # PVE Password

$vm_node	= "pve1"; # Name of PVE Node
$vm_type	= "qemu"; # qemu = KVM or LXC = lxc
$vm_id		= "100"; # ID LXC or VMID

# Login PVE (Create Ticket)
$api_pve_login = api_pve_login( $pve_server, $pve_port, $pve_user, $pve_pass );
$pve_ticket = $api_pve_login[ 'data' ][ 'ticket' ];
$pve_CSRFPreventionToke = $api_pve_login[ 'data' ][ 'CSRFPreventionToken' ];
# VNC urlencode Ticket ID
$pve_ticket2 = urlencode( $pve_ticket );

# VM Status
$params = [];
$vm_status = api_pve_con( $pve_server, $pve_port, "api2/json/nodes/$vm_node/$vm_type/$vm_id/status/current", "GET", $params, $pve_ticket, $pve_CSRFPreventionToke );
# VM Config
$params = [];
$vm_config = api_pve_con( $pve_server, $pve_port, "api2/json/nodes/$vm_node/$vm_type/$vm_id/config", "GET", $params, $pve_ticket, $pve_CSRFPreventionToke );
# VM TASK
$params = [ 'vmid' => $vm_id, 'source' => "all", 'limit' => "10" ];
$vm_tasks = api_pve_con( $pve_server, $pve_port, "api2/json/nodes/$vm_node/tasks", "GET", $params, $pve_ticket, $pve_CSRFPreventionToke );
# VM Backups
$params = [ 'vmid' => $vm_id, 'content' => 'backup' ];
$vm_backup = api_pve_con( $pve_server, $pve_port, "api2/json/nodes/$vm_node/storage/pbs_dus/content", "GET", $params, $pve_ticket, $pve_CSRFPreventionToke );
# VM Snapshot
$params = [];
$vm_snapshot = api_pve_con( $pve_server, $pve_port, "api2/json/nodes/$vm_node/$vm_type/$vm_id/snapshot", "GET", $params, $pve_ticket, $pve_CSRFPreventionToke );

# Output
echo "<pre>";
print_r($vm_status[ 'data' ]);
echo "</pre>";
?>
