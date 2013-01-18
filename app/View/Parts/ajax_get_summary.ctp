<strong>Part ID: </strong><? echo $part['Part']['ModelID']; ?><br/>
<strong>Name: </strong><? echo $part['Part']['ModelCode']; ?><br/>
<strong>Item Type: </strong><? echo $part['ItemType']['ItemTypeCode']; ?><br/>
<strong>UOM: </strong><? echo $part['Uom']['UOMCode']; ?><br/>
<strong>Default Released Location: </strong><? echo isset($part['DefaultLocation']['LocationCode'])?$part['DefaultLocation']['LocationCode']:'Not Set'; ?><br/>
<strong>Min on hand: </strong><? echo isset($part['Part']['MinOnHand'])?$part['Part']['MinOnHand'] . ' ' . $part['Uom']['UOMCode']:'Not Set'; ?><br/>
<strong>Max to order: </strong><? echo isset($part['Part']['MaxOrderTo'])?$part['Part']['MaxOrderTo'] . ' ' . $part['Uom']['UOMCode']:'Not set'; ?><br/>

    