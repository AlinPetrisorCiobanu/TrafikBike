#!/bin/bash
set -e

# Deshabilitar MPMs conflictivos
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true

# Activar solo mpm_prefork (necesario para PHP)
a2enmod mpm_prefork

# Activar mod_rewrite
a2enmod rewrite

# Iniciar Apache en foreground
exec apache2-foreground