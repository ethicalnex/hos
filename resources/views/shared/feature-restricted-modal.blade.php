<div class="modal fade" id="featureRestrictedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feature Not Available</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This feature is not available in your current plan.</p>
                <p>To unlock this feature, please upgrade your subscription.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('hospital.subscription.index') }}" class="btn btn-primary">
                    <i class="fas fa-crown me-1"></i> Upgrade Plan
                </a>
            </div>
        </div>
    </div>
</div>