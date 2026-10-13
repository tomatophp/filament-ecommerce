### v1.1.3

- Security hardening; upgrade recommended
- The order print page (`order.print`) is reached through temporary signed links from the Filament print actions, keyed by the order `uuid`, and requires the host app's `Order` policy `view` ability (no registered policy means access is denied). On panels with tenancy, the order must belong to the current tenant
- New `filament-ecommerce.print` config: `enabled` (or `FILAMENT_ECOMMERCE_PRINT_ROUTE=false`) to turn the print route off, `middleware`, and `expires` (minutes a print link stays valid)
- Cart rows require an existing product and a quantity of at least 1; price, discount, VAT and total are always computed from the product, and cart rows are re-priced from their products at web checkout
