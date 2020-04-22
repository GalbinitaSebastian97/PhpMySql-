<?php
class dbs {

    public $con;
    private static $instance = null;

    private function makeProcedures() {

        $sql = "DROP PROCEDURE IF EXISTS insertVehicle;
                CREATE PROCEDURE insertVehicle(
                        IN pVehiclesTitle varchar(150),
                        IN pVehiclesBrand int(11),
                        IN pVehiclesOverview longtext,
                        IN pPricePerDay int(11),
                        IN pFuelType varchar(100),
                        IN pModelYear int(6),
                        IN pSeatingCapacity int(11),
                        IN pVImage1 varchar(150),
                        IN pVImage2 varchar(150),
                        IN pVImage3 varchar(150),
                        IN pVImage4 varchar(150),
                        IN pVImage5 varchar(150),
                        IN pAirConditioner int(11),
                        IN pPowerDoorLocks int(11),
                        IN pAntiLockBrakingSystem int(11),
                        IN pBrakeAssist  int(11),
                        IN pPowerSteering int(11),
                        IN pDriverAirbag  int(11),
                        IN pPassengerAirbag  int(11),
                        IN pPowerWindows  int(11),
                        IN pCDPlayer  int(11),
                        IN pCentralLocking int(11),
                        IN pCrashSensor  int(11),
                        IN pLeatherSeats  int(11)
                        )
INSERT INTO tblvehicles(VehiclesTitle,VehiclesBrand,VehiclesOverview,PricePerDay,FuelType,ModelYear,SeatingCapacity,Vimage1,Vimage2,Vimage3,Vimage4,Vimage5,
                        AirConditioner,PowerDoorLocks,AntiLockBrakingSystem,BrakeAssist,PowerSteering,DriverAirbag,PassengerAirbag,PowerWindows,CDPlayer,CentralLocking,
                        CrashSensor,LeatherSeats)
VALUES (pVehiclesTitle,pVehiclesBrand,pVehiclesOverview,pPricePerDay,pFuelType,pModelYear,pSeatingCapacity,pVImage1,pVImage2,pVImage3,pVImage4,pVImage5,pAirConditioner,pPowerDoorLocks,
           pAntiLockBrakingSystem,pBrakeAssist,pPowerSteeringint,pDriverAirbag,pPassengerAirbag,pPowerWindows,pCDPlayer,pCentralLocking,pCrashSensor,pLeatherSeats);";
        $this->con->query($sql);

        $sql = "DROP PROCEDURE IF EXISTS updateVehicle
                CREATE PROCEDURE updateVehicle(
                        IN pid int(11),
                        IN pVehiclesTitle varchar(150),
                        IN pVehiclesBrand int(11),
                        IN pVehiclesOverview longtext,
                        IN pPricePerDay int(11),
                        IN pFuelType varchar(100),
                        IN pModelYear int(6),
                        IN pSeatingCapacity int(11),
                        IN pVImage1 varchar(150),
                        IN pVImage2 varchar(150),
                        IN pVImage3 varchar(150),
                        IN pVImage4 varchar(150),
                        IN pVImage5 varchar(150),
                        IN pAirConditioner int(11),
                        IN pPowerDoorLocks int(11),
                        IN pAntiLockBrakingSystem int(11),
                        IN pBrakeAssist  int(11),
                        IN pPowerSteering int(11),
                        IN pDriverAirbag  int(11),
                        IN pPassengerAirbag  int(11),
                        IN pPowerWindows  int(11),
                        IN pCDPlayer  int(11),
                        IN pCentralLocking int(11),
                        IN pCrashSensor  int(11),
                        IN pLeatherSeats  int(11)
                    )
                    BEGIN
UPDATE tblvehicles SET VehiclesTitle=pVehiclesTitle,VehiclesBrand=pVehiclesBrand,VehiclesOverview=pVehiclesOverview,PricePerDay=pPricePerDay,
                       FuelType=pFuelType,ModelYear=pModelYear,SeatingCapacity=pSeatingCapacity, VImage1= pVImage1, VImage2= pVImage2, VImage3= pVImage3,
                       VImage4= pVImage4, VImage5=pVImage5,AirConditioner=pAirConditioner,PowerDoorLocks=pPowerDoorLocks,AntiLockBrakingSystem=pAntiLockBrakingSystem,
                       BrakeAssist=pBrakeAssist,PowerSteering=pPowerSteering,PowerWindows=pPowerWindows,CDPlayer=pCDPlayer,CentralLocking=pCentralLocking,
                       CrashSensor=pCrashSensor,LeatherSeats=pLeatherSeats
WHERE id=pid;
END;";
        $this->con->query($sql);
        
        
        $dOrderUser = "DROP PROCEDURE IF EXISTS orderUsersByFullName";
        $cOrderUser="CREATE PROCEDURE orderUsersByFullName()
                BEGIN 
                SELECT * FROM tblusers
                ORDER BY FullName ASC;
                END;";
        $this->con->query($dOrderUser);
        $this->con->query($cOrderUser);
    }

    private function makeTriggers() {
        $drop ="DROP TRIGGER IF EXISTS insertVehicleStatus;";
        $sql = "CREATE TRIGGER insertVehicleStatus
                AFTER INSERT ON tblvehicles
                FOR EACH ROW
                BEGIN
                INSERT INTO logsvehicles VALUES (null,NEW.id,'Inserted',NOW());
                END;";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP TRIGGER IF EXISTS updateVehicleStatus;";
        $sql = "CREATE TRIGGER updateVehicleStatus
                AFTER UPDATE ON tblvehicles
                FOR EACH ROW
                BEGIN
                INSERT INTO logsvehicles VALUES (null,NEW.id,'Updated',NOW());
                END";
        $this->con->query($drop);
        $this->con->query($sql);

        $drop = "DROP TRIGGER IF EXISTS deleteVehicleStatus;";
        $sql = "CREATE TRIGGER deleteVehicleStatus
                BEFORE DELETE ON tblvehicles
                FOR EACH ROW
                BEGIN
                INSERT INTO logsvehicles VALUES (null,OLD.id,'DELETED',NOW());
                END";
        $this->con->query($drop);
        $this->con->query($sql);
    }
    private function __construct() {
        $host = 'localhost';
        $dbms = 'mysql';
        $db = 'books';
        $user = 'root';
        $pass = '';
        $dsn = "$dbms:host=$host;dbname=$db";
        $this->con = new PDO($dsn, $user, $pass);
        $this->makeProcedures();
        $this->makeTriggers();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new dbs();
        }
        return self::$instance;
    }

}
