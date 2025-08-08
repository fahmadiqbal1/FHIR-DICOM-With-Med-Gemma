"""Database connection and session management"""

import os
import logging
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, Session
from sqlalchemy.pool import StaticPool
from contextlib import contextmanager
from .models import Base
import yaml

logger = logging.getLogger(__name__)

class DatabaseManager:
    """Database connection and session management"""
    
    def __init__(self, config_path: str = None):
        self.engine = None
        self.SessionLocal = None
        self.config_path = config_path or "config/database.yaml"
        self._initialize_database()
    
    def _initialize_database(self):
        """Initialize database connection"""
        try:
            # Get database URL from environment or config
            database_url = self._get_database_url()
            
            # Create engine
            self.engine = create_engine(
                database_url,
                echo=os.getenv('DB_ECHO', 'false').lower() == 'true',
                pool_pre_ping=True,
                pool_size=int(os.getenv('DB_POOL_SIZE', '5')),
                max_overflow=int(os.getenv('DB_MAX_OVERFLOW', '10'))
            )
            
            # Create session factory
            self.SessionLocal = sessionmaker(
                autocommit=False,
                autoflush=False,
                bind=self.engine
            )
            
            logger.info("Database initialized successfully")
            
        except Exception as e:
            logger.error(f"Error initializing database: {str(e)}")
            raise
    
    def _get_database_url(self) -> str:
        """Get database URL from environment or config file"""
        # Try environment variable first
        database_url = os.getenv('DATABASE_URL')
        if database_url:
            return database_url
        
        # Try config file
        try:
            if os.path.exists(self.config_path):
                with open(self.config_path, 'r') as f:
                    config = yaml.safe_load(f)
                
                environment = os.getenv('ENVIRONMENT', 'development')
                db_config = config.get(environment, {})
                
                if db_config:
                    # Build database URL from config
                    driver = db_config.get('driver', 'postgresql')
                    host = db_config.get('host', 'localhost')
                    port = db_config.get('port', 5432)
                    database = db_config.get('database', 'fhir_dicom_dev')
                    username = db_config.get('username', 'postgres')
                    password = db_config.get('password', 'postgres')
                    
                    return f"{driver}://{username}:{password}@{host}:{port}/{database}"
        
        except Exception as e:
            logger.warning(f"Error reading database config: {str(e)}")
        
        # Default fallback
        return "postgresql://postgres:postgres@localhost:5432/fhir_dicom_dev"
    
    def create_tables(self):
        """Create all database tables"""
        try:
            Base.metadata.create_all(bind=self.engine)
            logger.info("Database tables created successfully")
        except Exception as e:
            logger.error(f"Error creating database tables: {str(e)}")
            raise
    
    def drop_tables(self):
        """Drop all database tables"""
        try:
            Base.metadata.drop_all(bind=self.engine)
            logger.info("Database tables dropped successfully")
        except Exception as e:
            logger.error(f"Error dropping database tables: {str(e)}")
            raise
    
    @contextmanager
    def get_session(self):
        """Get database session with automatic cleanup"""
        session = self.SessionLocal()
        try:
            yield session
            session.commit()
        except Exception:
            session.rollback()
            raise
        finally:
            session.close()
    
    def get_db_session(self) -> Session:
        """Get database session for dependency injection"""
        return self.SessionLocal()
    
    def check_connection(self) -> bool:
        """Check database connection"""
        try:
            with self.get_session() as session:
                session.execute("SELECT 1")
            return True
        except Exception as e:
            logger.error(f"Database connection check failed: {str(e)}")
            return False
    
    def get_database_info(self) -> dict:
        """Get database information"""
        try:
            with self.get_session() as session:
                result = session.execute("SELECT version()")
                version = result.fetchone()[0] if result else "Unknown"
                
                return {
                    'connected': True,
                    'version': version,
                    'url': self.engine.url.render_as_string(hide_password=True)
                }
        except Exception as e:
            return {
                'connected': False,
                'error': str(e)
            }

# Global database manager instance
db_manager = DatabaseManager()

def get_db_session() -> Session:
    """Dependency function for FastAPI"""
    session = db_manager.get_db_session()
    try:
        yield session
    finally:
        session.close()

def init_database():
    """Initialize database tables"""
    db_manager.create_tables()

def check_database():
    """Check database connection and return status"""
    return db_manager.check_connection()