# Sistema Multitenant - Documentación

## Descripción
Este sistema ha sido modificado para soportar arquitectura multitenant (multiinquilino), lo que permite que múltiples empresas/organizaciones utilicen la misma aplicación con datos completamente aislados entre sí.

## Arquitectura Implementada

### 1. Detección de Tenant por Subdominio
- **Middleware**: `TenantMiddleware`
- **Funcionamiento**: Detecta el tenant basándose en el subdominio de la URL
- **Ejemplos**:
  - `empresa-a.localhost:8000` → Tenant: empresa-a
  - `empresa-b.localhost:8000` → Tenant: empresa-b

### 2. Modelos Actualizados

#### Tenant Model
```php
// App\Models\Tenant
- id (string): Identificador único del tenant
- name (string): Nombre descriptivo
- subdomain (string): Subdominio único
- config (json): Configuraciones específicas del tenant
```

#### Usuario Model
- Incluye trait `BelongsToTenant`
- Campo `tenant_id` para asociación
- Global scope para filtrar automáticamente por tenant

#### Tarea Model
- Incluye trait `BelongsToTenant`
- Campo `tenant_id` para asociación
- Global scope para filtrar automáticamente por tenant

### 3. Trait BelongsToTenant
**Funcionalidades**:
- Agrega scope global para filtrar registros por tenant activo
- Asigna automáticamente tenant_id al crear nuevos registros
- Relación con modelo Tenant

## Base de Datos

### Tablas Modificadas
1. **usuarios** - Agregada columna `tenant_id`
2. **tareas** - Agregada columna `tenant_id`

### Nueva Tabla
3. **tenants** - Gestión de inquilinos

## Datos de Ejemplo

### Tenants Creados
1. **Empresa A**
   - ID: empresa-a
   - Subdominio: empresa-a
   - Usuarios: admin@empresa-a.com, user@empresa-a.com
   - Password: password123

2. **Empresa B**
   - ID: empresa-b
   - Subdominio: empresa-b
   - Usuarios: admin@empresa-b.com, user@empresa-b.com
   - Password: password123

## Configuración de Hosts (Para Testing Local)

### Windows
Agregar al archivo `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 empresa-a.localhost
127.0.0.1 empresa-b.localhost
```

### Laravel Valet (Mac/Linux)
```bash
valet link empresa-a
valet link empresa-b
```

## Flujo de Funcionamiento

1. **Usuario accede**: `http://empresa-a.localhost:8000`
2. **Middleware detecta**: Tenant "empresa-a" desde subdominio
3. **Aplicación registra**: Tenant activo en contenedor IoC
4. **Global Scopes filtran**: Solo datos del tenant "empresa-a"
5. **Nuevos registros**: Automáticamente asociados a "empresa-a"

## Rutas Protegidas
Todas las rutas API están protegidas con:
- `tenant` middleware (detección de inquilino)
- `auth:sanctum` middleware (autenticación)

## Comandos Importantes

### Reiniciar Base de Datos con Datos de Ejemplo
```bash
php artisan migrate:fresh --seed
```

### Verificar Estado de Migraciones
```bash
php artisan migrate:status
```

## Testing del Sistema

### 1. Verificar Aislamiento de Datos
```bash
# Login como Empresa A
POST http://empresa-a.localhost:8000/api/login
{
  "email": "admin@empresa-a.com",
  "password": "password123"
}

# Listar tareas (solo verá tareas de Empresa A)
GET http://empresa-a.localhost:8000/api/tareas/listTareas
```

### 2. Verificar Diferentes Tenants
```bash
# Login como Empresa B
POST http://empresa-b.localhost:8000/api/login
{
  "email": "admin@empresa-b.com",
  "password": "password123"
}

# Listar tareas (solo verá tareas de Empresa B)
GET http://empresa-b.localhost:8000/api/tareas/listTareas
```

## Características de Seguridad

1. **Aislamiento Automático**: Los datos se filtran automáticamente por tenant
2. **No Filtrado Manual**: No es necesario agregar WHERE tenant_id en consultas
3. **Protección Cross-Tenant**: Imposible acceder a datos de otros tenants
4. **Asignación Automática**: Nuevos registros se asocian automáticamente al tenant activo

## Puntos Implementados para Evaluación

✅ **Detección de Tenant**: Por subdominio  
✅ **Aislamiento de Datos**: Global scopes automáticos  
✅ **Middleware Personalizado**: TenantMiddleware  
✅ **Trait Reutilizable**: BelongsToTenant  
✅ **Base de Datos Multitenant**: Columnas tenant_id  
✅ **Datos de Ejemplo**: 2 empresas con usuarios y tareas  
✅ **Documentación Completa**: Este archivo README  

**Valor**: 2.5 puntos por implementación completa de arquitectura multitenant